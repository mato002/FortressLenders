@component('mail::message')
# New Loan Application Received

A new loan application has been submitted on the Fortress Lenders website.

@component('mail::panel')
- **Full Name**: {{ $application->full_name }}
- **Phone**: {{ $application->phone }}
- **Email**: {{ $application->email ?? 'N/A' }}
- **Date of Birth**: {{ optional($application->date_of_birth)->format('d M Y') ?? 'N/A' }}
- **Town**: {{ $application->town ?? 'N/A' }}
- **Residence**: {{ $application->residence ?? 'N/A' }}
- **Client Type**: {{ ucfirst($application->client_type ?? 'N/A') }}
- **Loan Type**: {{ $application->loan_type }}
- **Amount Requested**: KES {{ number_format($application->amount_requested, 2) }}
- **Repayment Period**: {{ $application->repayment_period }}
@endcomponent

**Purpose of Loan**

{{ $application->purpose ?? 'N/A' }}

@component('mail::button', ['url' => route('admin.loan-applications.index')])
View in Admin Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent







