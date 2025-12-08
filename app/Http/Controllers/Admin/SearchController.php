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
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
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
        try {
            $results['products'] = Product::where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('summary', 'like', "%{$query}%")
                      ->orWhere('category', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($product) {
                    try {
                        return [
                            'id' => $product->id,
                            'title' => $product->title,
                            'description' => Str::limit(strip_tags($product->description ?? $product->summary ?? ''), 100),
                            'url' => route('admin.products.show', $product->id),
                            'type' => 'Product',
                            'icon' => 'package'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['products'] = collect();
        }

        // Search Job Posts
        try {
            $results['jobs'] = JobPost::where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('department', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($job) {
                    try {
                        return [
                            'id' => $job->id,
                            'title' => $job->title,
                            'description' => Str::limit(strip_tags($job->description ?? ''), 100),
                            'url' => route('admin.jobs.show', $job->id),
                            'type' => 'Job Post',
                            'icon' => 'briefcase'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['jobs'] = collect();
        }

        // Search Job Applications
        try {
            $results['job_applications'] = JobApplication::with('jobPost')
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($application) {
                    try {
                        return [
                            'id' => $application->id,
                            'title' => $application->name,
                            'description' => $application->email . ' - ' . ($application->jobPost?->title ?? 'N/A'),
                            'url' => route('admin.job-applications.show', $application->id),
                            'type' => 'Job Application',
                            'icon' => 'file-text'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['job_applications'] = collect();
        }

        // Search Contact Messages
        try {
            $results['contact_messages'] = ContactMessage::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('subject', 'like', "%{$query}%")
                      ->orWhere('message', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($message) {
                    try {
                        return [
                            'id' => $message->id,
                            'title' => $message->subject ?? $message->name,
                            'description' => Str::limit($message->message ?? '', 100),
                            'url' => route('admin.contact-messages.show', $message->id),
                            'type' => 'Contact Message',
                            'icon' => 'mail'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['contact_messages'] = collect();
        }

        // Search Loan Applications
        try {
            $results['loan_applications'] = LoanApplication::where(function($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($application) {
                    try {
                        return [
                            'id' => $application->id,
                            'title' => $application->full_name ?? 'N/A',
                            'description' => ($application->email ?? '') . ' - ' . ($application->loan_type ?? 'N/A'),
                            'url' => route('admin.loan-applications.show', $application->id),
                            'type' => 'Loan Application',
                            'icon' => 'dollar-sign'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['loan_applications'] = collect();
        }

        // Search Team Members
        try {
            $results['team_members'] = TeamMember::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('position', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($member) {
                    try {
                        return [
                            'id' => $member->id,
                            'title' => $member->name,
                            'description' => $member->position,
                            'url' => route('admin.team-members.edit', $member->id),
                            'type' => 'Team Member',
                            'icon' => 'user'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['team_members'] = collect();
        }

        // Search Branches
        try {
            $results['branches'] = Branch::where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('address_line1', 'like', "%{$query}%")
                      ->orWhere('city', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get()
                ->map(function ($branch) {
                    try {
                        return [
                            'id' => $branch->id,
                            'title' => $branch->name,
                            'description' => $branch->address_line1 . ', ' . ($branch->city ?? ''),
                            'url' => route('admin.branches.edit', $branch->id),
                            'type' => 'Branch',
                            'icon' => 'map-pin'
                        ];
                    } catch (\Exception $e) {
                        return null;
                    }
                })->filter();
        } catch (\Exception $e) {
            $results['branches'] = collect();
        }

        // Flatten results
        $allResults = collect($results)->flatten(1)->take(10)->values();

        return response()->json([
            'results' => $allResults,
            'total' => $allResults->count(),
            'query' => $query
        ]);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage(), [
                'query' => $request->get('q'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'results' => [],
                'message' => 'An error occurred while searching. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 200);
        }
    }
}

