<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(Request $request): View
    {
        $query = Faq::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $totalFaqsCount = Faq::count();
        $filteredFaqsCount = $query->count();

        $faqs = $query->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs', 'totalFaqsCount', 'filteredFaqsCount'));
    }

    public function create(): View
    {
        $faq = new Faq([
            'is_active' => true,
            'display_order' => Faq::count() + 1,
        ]);

        return view('admin.faqs.create', compact('faq'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        Faq::create($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('status', 'FAQ created successfully.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $this->validatedData($request);
        $faq->update($data);

        return redirect()
            ->route('admin.faqs.index')
            ->with('status', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('status', 'FAQ deleted successfully.');
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['display_order'] = $validated['display_order'] ?? Faq::count() + 1;

        return $validated;
    }
}
