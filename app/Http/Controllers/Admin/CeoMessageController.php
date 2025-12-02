<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CeoMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CeoMessageController extends Controller
{
    public function index(): View
    {
        $ceoMessages = CeoMessage::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.ceo-messages.index', compact('ceoMessages'));
    }

    public function create(): View
    {
        $ceoMessage = new CeoMessage([
            'is_active' => true,
        ]);

        return view('admin.ceo-messages.create', compact('ceoMessage'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $ceoMessage = CeoMessage::create($data);
        $this->handleImageUpload($request, $ceoMessage);

        return redirect()
            ->route('admin.ceo-messages.index')
            ->with('status', 'CEO Message created successfully.');
    }

    public function edit(CeoMessage $ceoMessage): View
    {
        return view('admin.ceo-messages.edit', compact('ceoMessage'));
    }

    public function update(Request $request, CeoMessage $ceoMessage): RedirectResponse
    {
        $data = $this->validatedData($request);
        $ceoMessage->update($data);
        $this->handleImageUpload($request, $ceoMessage);

        return redirect()
            ->route('admin.ceo-messages.index')
            ->with('status', 'CEO Message updated successfully.');
    }

    public function destroy(CeoMessage $ceoMessage): RedirectResponse
    {
        if ($ceoMessage->image_path) {
            Storage::disk('public')->delete($ceoMessage->image_path);
        }

        $ceoMessage->delete();

        return back()->with('status', 'CEO Message deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    protected function handleImageUpload(Request $request, CeoMessage $ceoMessage): void
    {
        if (!$request->hasFile('image')) {
            return;
        }

        if ($ceoMessage->image_path) {
            Storage::disk('public')->delete($ceoMessage->image_path);
        }

        $path = $request->file('image')->store('ceo-messages', 'public');
        $ceoMessage->update(['image_path' => $path]);
    }
}
