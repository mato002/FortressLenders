<p>Hello {{ $contact->name }},</p>

<p>
    Thank you for contacting Fortress Lenders Ltd. We have received your message
    and one of our team members will follow up shortly.
</p>

<p><strong>Your submission:</strong></p>
<ul>
    <li><strong>Subject:</strong> {{ $contact->subject ?: 'General inquiry' }}</li>
    <li><strong>Message:</strong> {{ $contact->message }}</li>
</ul>

<p>
    If your request is urgent, please call us on +254 743 838 312 or
    email <a href="mailto:info@fortresslenders.com">info@fortresslenders.com</a>.
</p>

<p>
    Regards,<br>
    Fortress Lenders Support Team
</p>







