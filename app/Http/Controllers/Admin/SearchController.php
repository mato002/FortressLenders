<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\ContactMessage;
use App\Models\LoanApplication;
use App\Models\TeamMember;
use App\Models\Branch;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'results' => [],
                'message' => 'Please enter at least 2 characters to search'
            ]);
        }

        $results = [
            'products' => [],
            'jobs' => [],
            'job_applications' => [],
            'contact_messages' => [],
            'loan_applications' => [],
            'team_members' => [],
            'branches' => [],
        ];

        // Search Products
        $results['products'] = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->name,
                    'description' => \Str::limit(strip_tags($product->description), 100),
                    'url' => route('admin.products.show', $product->id),
                    'type' => 'Product',
                    'icon' => 'package'
                ];
            });

        // Search Job Posts
        $results['jobs'] = JobPost::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('department', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'description' => \Str::limit(strip_tags($job->description), 100),
                    'url' => route('admin.jobs.show', $job->id),
                    'type' => 'Job Post',
                    'icon' => 'briefcase'
                ];
            });

        // Search Job Applications
        $results['job_applications'] = JobApplication::with('jobPost')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'title' => $application->name,
                    'description' => $application->email . ' - ' . ($application->jobPost?->title ?? 'N/A'),
                    'url' => route('admin.job-applications.show', $application->id),
                    'type' => 'Job Application',
                    'icon' => 'file-text'
                ];
            });

        // Search Contact Messages
        $results['contact_messages'] = ContactMessage::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('subject', 'like', "%{$query}%")
            ->orWhere('message', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'title' => $message->subject ?? $message->name,
                    'description' => \Str::limit($message->message, 100),
                    'url' => route('admin.contact-messages.show', $message->id),
                    'type' => 'Contact Message',
                    'icon' => 'mail'
                ];
            });

        // Search Loan Applications
        $results['loan_applications'] = LoanApplication::where('full_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'title' => $application->full_name ?? 'N/A',
                    'description' => ($application->email ?? '') . ' - ' . ($application->loan_type ?? 'N/A'),
                    'url' => route('admin.loan-applications.show', $application->id),
                    'type' => 'Loan Application',
                    'icon' => 'dollar-sign'
                ];
            });

        // Search Team Members
        $results['team_members'] = TeamMember::where('name', 'like', "%{$query}%")
            ->orWhere('position', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'title' => $member->name,
                    'description' => $member->position,
                    'url' => route('admin.team-members.show', $member->id),
                    'type' => 'Team Member',
                    'icon' => 'user'
                ];
            });

        // Search Branches
        $results['branches'] = Branch::where('name', 'like', "%{$query}%")
            ->orWhere('address_line1', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($branch) {
                return [
                    'id' => $branch->id,
                    'title' => $branch->name,
                    'description' => $branch->address_line1 . ', ' . ($branch->city ?? ''),
                    'url' => route('admin.branches.show', $branch->id),
                    'type' => 'Branch',
                    'icon' => 'map-pin'
                ];
            });

        // Flatten results
        $allResults = collect($results)->flatten(1)->take(10)->values();

        return response()->json([
            'results' => $allResults,
            'total' => $allResults->count(),
            'query' => $query
        ]);
    }
}

