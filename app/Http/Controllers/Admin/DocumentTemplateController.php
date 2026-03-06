<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateController extends Controller
{
    public function index(): View
    {
        $templates = DocumentTemplate::whereIn('document_type', ['offer_letter', 'contract'])
            ->get()
            ->keyBy('document_type');

        return view('admin.document-templates.index', compact('templates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:offer_letter,contract'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $file = $validated['file'] ?? $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('document_templates', $filename, 'private');

        $existing = DocumentTemplate::where('document_type', $validated['document_type'])->first();
        if ($existing) {
            Storage::disk('private')->delete($existing->file_path);
            $existing->delete();
        }

        DocumentTemplate::create([
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Template updated successfully.');
    }
}

