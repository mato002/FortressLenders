<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Display help and FAQ page
     */
    public function index()
    {
        $candidate = current_portal_candidate();
        
        if (!$candidate) {
            abort(403, 'Unauthorized');
        }
        
        return view('candidate.help', compact('candidate'));
    }
}
