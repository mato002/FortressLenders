<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateDocument;
use App\Models\DocumentTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateDocumentController extends Controller
{
    /**
     * Store an HR-uploaded document/template.
     *
     * For offer_letter / contract we treat this as a global template that
     * appears for all candidates. For other types (id, kra, sha) the
     * document is attached to this specific candidate.
     */
    public function store(Request $request, Candidate $candidate): RedirectResponse
    {
        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:offer_letter,contract,id,kra,sha'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $validated['file'] ?? $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Shared templates: offer letter & contract
        if (in_array($validated['document_type'], ['offer_letter', 'contract'], true)) {
            $path = $file->storeAs('document_templates', $filename, 'private');

            // Replace existing template for this type
            $existingTemplate = DocumentTemplate::where('document_type', $validated['document_type'])->first();
            if ($existingTemplate) {
                Storage::disk('private')->delete($existingTemplate->file_path);
                $existingTemplate->delete();
            }

            DocumentTemplate::create([
                'document_type' => $validated['document_type'],
                'file_path' => $path,
                'original_filename' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => $request->user()->id,
            ]);

            return back()->with('success', 'Template updated. All candidates can now download the latest version.');
        }

        // Per-candidate documents (ID / KRA / SHA)
        $path = $file->storeAs('candidate_documents/' . $candidate->id, $filename, 'private');

        // Delete existing document of the same type for this candidate
        $existing = CandidateDocument::where('candidate_id', $candidate->id)
            ->where('document_type', $validated['document_type'])
            ->first();

        if ($existing) {
            Storage::disk('private')->delete($existing->file_path);
            $existing->delete();
        }

        CandidateDocument::create([
            'candidate_id' => $candidate->id,
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'approved',
            'uploaded_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully for this candidate.');
    }

    /**
     * Download a candidate document as admin/HR.
     */
    public function download(Candidate $candidate, CandidateDocument $document)
    {
        if ($document->candidate_id !== $candidate->id) {
            abort(404);
        }

        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_filename ?? 'document.pdf'
        );
    }

    /**
     * Delete a candidate document (e.g. to replace an outdated template).
     */
    public function destroy(Candidate $candidate, CandidateDocument $document): RedirectResponse
    {
        if ($document->candidate_id !== $candidate->id) {
            abort(404);
        }

        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }
}

