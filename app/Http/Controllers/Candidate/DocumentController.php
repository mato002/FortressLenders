<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display all documents.
     */
    public function index()
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        $documents = $candidate->documents()->orderBy('created_at', 'desc')->get();
        
        // Group documents by type
        $groupedDocuments = [
            'offer_letter' => $documents->where('document_type', 'offer_letter')->first(),
            'filled_offer_letter' => $documents->where('document_type', 'filled_offer_letter')->first(),
            'contract' => $documents->where('document_type', 'contract')->first(),
            'filled_contract' => $documents->where('document_type', 'filled_contract')->first(),
            'id' => $documents->where('document_type', 'id')->first(),
            'kra' => $documents->where('document_type', 'kra')->first(),
            'sha' => $documents->where('document_type', 'sha')->first(),
        ];

        return view('candidate.documents.index', compact('candidate', 'groupedDocuments'));
    }

    /**
     * Download a document.
     */
    public function download(CandidateDocument $document)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate || $document->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized.');
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
     * Upload a document.
     */
    public function upload(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:filled_offer_letter,filled_contract,id,kra,sha'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('candidate_documents/' . $candidate->id, $filename, 'private');

        // Delete existing document of the same type if exists
        $existingDocument = CandidateDocument::where('candidate_id', $candidate->id)
            ->where('document_type', $validated['document_type'])
            ->first();

        if ($existingDocument) {
            Storage::disk('private')->delete($existingDocument->file_path);
            $existingDocument->delete();
        }

        CandidateDocument::create([
            'candidate_id' => $candidate->id,
            'document_type' => $validated['document_type'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('candidate.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Delete a document.
     */
    public function destroy(CandidateDocument $document)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate || $document->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized.');
        }

        // Only allow deletion of documents uploaded by candidate (not HR uploaded ones)
        if ($document->uploaded_by === null) {
            Storage::disk('private')->delete($document->file_path);
            $document->delete();

            return redirect()->route('candidate.documents.index')
                ->with('success', 'Document deleted successfully.');
        }

        return redirect()->route('candidate.documents.index')
            ->with('error', 'You cannot delete documents uploaded by HR.');
    }
}
