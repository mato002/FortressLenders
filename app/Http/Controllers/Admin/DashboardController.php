<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ContactMessage;
use App\Models\LoanApplication;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'unread_messages' => ContactMessage::whereNull('handled_at')->count(),
            'total_messages' => ContactMessage::count(),
            'pending_applications' => LoanApplication::where('status', 'pending')->count(),
            'total_applications' => LoanApplication::count(),
            'branches' => Branch::count(),
            'active_branches' => Branch::where('is_active', true)->count(),
        ];

        $recentMessages = ContactMessage::latest()->limit(5)->get();
        $recentApplications = LoanApplication::latest()->limit(5)->get();
        $latestProducts = Product::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages', 'recentApplications', 'latestProducts'));
    }
}
