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
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized');
        }
        
        return view('candidate.help', compact('candidate'));
    }
}
