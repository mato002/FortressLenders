<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display candidate notifications
     */
    public function index()
    {
        $candidate = current_portal_candidate();
        
        if (!$candidate) {
            abort(403, 'Unauthorized');
        }
        
        return view('candidate.notifications', compact('candidate'));
    }
}
