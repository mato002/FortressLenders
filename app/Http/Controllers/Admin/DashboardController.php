<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\LoanApplication;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Product;
use App\Models\TeamMember;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Products
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            
            // Messages
            'unread_messages' => ContactMessage::whereNull('handled_at')->count(),
            'total_messages' => ContactMessage::count(),
            
            // Loan Applications
            'pending_loan_applications' => LoanApplication::where('status', 'pending')->count(),
            'total_loan_applications' => LoanApplication::count(),
            'approved_loan_applications' => LoanApplication::where('status', 'approved')->count(),
            
            // Job Applications
            'pending_job_applications' => JobApplication::where('status', 'pending')->count(),
            'total_job_applications' => JobApplication::count(),
            'shortlisted_job_applications' => JobApplication::where('status', 'shortlisted')->count(),
            'hired_job_applications' => JobApplication::where('status', 'hired')->count(),
            
            // Job Posts
            'active_job_posts' => JobPost::where('is_active', true)->count(),
            'total_job_posts' => JobPost::count(),
            
            // Branches
            'branches' => Branch::count(),
            'active_branches' => Branch::where('is_active', true)->count(),
            
            // Content
            'team_members' => TeamMember::count(),
            'blog_posts' => Post::count(),
            'faqs' => Faq::count(),
            
            // Subscribers
            'newsletter_subscribers' => NewsletterSubscriber::count(),
            
            // Users
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];

        $recentMessages = ContactMessage::latest()->limit(10)->get();
        $recentLoanApplications = LoanApplication::latest()->limit(10)->get();
        $recentJobApplications = JobApplication::with('jobPost')->latest()->limit(10)->get();
        $latestProducts = Product::latest()->limit(10)->get();
        $recentActivityLogs = ActivityLog::with('user')->latest()->limit(10)->get();
        $latestJobPosts = JobPost::latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentMessages',
            'recentLoanApplications',
            'recentJobApplications',
            'latestProducts',
            'recentActivityLogs',
            'latestJobPosts'
        ));
    }
}
