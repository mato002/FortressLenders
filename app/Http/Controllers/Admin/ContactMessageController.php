<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status'))
            )
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'new' => ContactMessage::where('status', 'new')->count(),
            'in_progress' => ContactMessage::where('status', 'in_progress')->count(),
            'handled' => ContactMessage::where('status', 'handled')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'statusCounts'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->load('replies.sender');
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function sendReply(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $validated = $request->validate([
            'channel' => 'required|in:email,sms,whatsapp',
            'message' => 'required|string|max:5000',
            'recipient' => 'required|string',
        ]);

        // Validate recipient based on channel
        if ($validated['channel'] === 'email') {
            $request->validate(['recipient' => 'email']);
        } else {
            $request->validate(['recipient' => 'regex:/^[0-9+\-\s()]+$/']);
        }

        // Create reply record
        $reply = ContactMessageReply::create([
            'contact_message_id' => $contactMessage->id,
            'sent_by' => auth()->id(),
            'channel' => $validated['channel'],
            'message' => $validated['message'],
            'recipient' => $validated['recipient'],
            'status' => 'pending',
        ]);

        // Send the message
        $messagingService = new MessagingService();
        $sent = $messagingService->send($reply);

        if ($sent) {
            // Update contact message status if needed
            if ($contactMessage->status === 'new') {
                $contactMessage->update(['status' => 'in_progress']);
            }

            return back()->with('status', 'Reply sent successfully via ' . strtoupper($validated['channel']) . '!');
        } else {
            return back()->withErrors(['message' => 'Failed to send reply. Please check the error and try again.']);
        }
    }

    public function update(Request $request, ContactMessage $contactMessage): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,in_progress,handled'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $contactMessage->fill($data);

        if ($data['status'] === 'handled' && ! $contactMessage->handled_at) {
            $contactMessage->handled_at = now();
        } elseif ($data['status'] !== 'handled') {
            $contactMessage->handled_at = null;
        }

        $contactMessage->save();

        return back()->with('status', 'Message updated successfully.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('status', 'Message deleted.');
    }
}
