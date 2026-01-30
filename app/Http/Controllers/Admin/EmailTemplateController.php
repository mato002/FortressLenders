<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmailTemplateController
{
    public function index(): View
    {
        $templates = EmailTemplate::orderBy('template_type')->orderBy('name')->paginate(20);
        
        return view('admin.email-templates.index', [
            'templates' => $templates,
        ]);
    }

    public function create(): View
    {
        $templateTypes = [
            'job_application' => 'Job Application',
            'loan_application' => 'Loan Application',
            'contact_message' => 'Contact Message',
            'custom' => 'Custom',
        ];

        return view('admin.email-templates.create', [
            'templateTypes' => $templateTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:email_templates|max:255',
            'slug' => 'required|string|unique:email_templates|max:255',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'template_type' => 'required|in:job_application,loan_application,contact_message,custom',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        
        EmailTemplate::create($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $template): View
    {
        $templateTypes = [
            'job_application' => 'Job Application',
            'loan_application' => 'Loan Application',
            'contact_message' => 'Contact Message',
            'custom' => 'Custom',
        ];

        return view('admin.email-templates.edit', [
            'template' => $template,
            'templateTypes' => $templateTypes,
        ]);
    }

    public function update(Request $request, EmailTemplate $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:email_templates,name,' . $template->id . '|max:255',
            'slug' => 'required|string|unique:email_templates,slug,' . $template->id . '|max:255',
            'subject' => 'required|string|max:500',
            'body' => 'required|string',
            'template_type' => 'required|in:job_application,loan_application,contact_message,custom',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }

    public function preview(EmailTemplate $template): View
    {
        $sampleData = $this->getSampleData($template->template_type);
        $rendered = $template->render($sampleData);

        return view('admin.email-templates.preview', [
            'template' => $template,
            'rendered' => $rendered,
            'sampleData' => $sampleData,
        ]);
    }

    private function getSampleData(string $type): array
    {
        return match($type) {
            'job_application' => [
                'candidate_name' => 'John Doe',
                'job_title' => 'Senior Software Engineer',
                'company_name' => 'Fortress Lenders Ltd',
                'status' => 'Under Review',
                'application_date' => '2026-01-30',
            ],
            'loan_application' => [
                'applicant_name' => 'Jane Smith',
                'loan_amount' => 'KES 500,000',
                'loan_type' => 'Personal Loan',
                'status' => 'Under Review',
                'application_date' => '2026-01-30',
            ],
            'contact_message' => [
                'name' => 'John Contact',
                'email' => 'john@example.com',
                'subject' => 'Inquiry about loan products',
                'message' => 'I am interested in learning more about your loan products.',
            ],
            default => [],
        };
    }
}
