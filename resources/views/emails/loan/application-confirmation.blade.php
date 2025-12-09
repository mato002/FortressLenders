@component('mail::message')
# Loan Application Received

Dear {{ $application->full_name }},

Thank you for applying for a loan with Fortress Lenders Ltd. We have received your application and our team will review it shortly.

@component('mail::panel')
- **Loan Type**: {{ $application->loan_type }}
- **Amount Requested**: KES {{ number_format($application->amount_requested, 2) }}
- **Repayment Period**: {{ $application->repayment_period }}
@endcomponent

If we need any additional information, we will contact you using the phone number or email you provided.

Thanks,<br>
{{ config('app.name') }}
@endcomponent







