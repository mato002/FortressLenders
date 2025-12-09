<h1>New Contact Message</h1>

<p>A new inquiry was submitted via the Fortress Lenders website.</p>

<ul>
    <li><strong>Name:</strong> {{ $contact->name }}</li>
    <li><strong>Email:</strong> {{ $contact->email }}</li>
    <li><strong>Phone:</strong> {{ $contact->phone ?: 'N/A' }}</li>
    <li><strong>Subject:</strong> {{ $contact->subject ?: '—' }}</li>
    <li><strong>Submitted:</strong> {{ $contact->created_at->format('M d, Y g:i A') }}</li>
</ul>

<p><strong>Message:</strong></p>
<p>{{ $contact->message }}</p>

<p>
    Manage this request in the admin panel:
    <a href="{{ config('app.url') }}/admin/contact-messages/{{ $contact->id }}">
        View message
    </a>.
</p>







