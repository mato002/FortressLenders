@component('emails.components.message')
<h1>Your Portal Login Credentials</h1>

<p>Dear {{ $teamMember->name }},</p>

<p>Your portal access has been created! You can now log in to access your candidate dashboard, where you can manage your bio data, documents, appraisals, and more.</p>

<div class="message-box" style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin: 20px 0;">
    <p style="margin: 0; font-size: 16px; color: #92400e; font-weight: 600;">🔐 Your Login Credentials</p>
    <p style="margin: 12px 0 0 0; color: #374151;">
        <strong>Email:</strong> {{ $teamMember->email }}<br>
        <strong>Password:</strong> <code style="background-color: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 14px;">{{ $password }}</code>
    </p>
    <p style="margin: 12px 0 0 0; color: #92400e; font-size: 14px;">
        <strong>⚠️ Important:</strong> Please save this password securely. You can change it after logging in.
    </p>
</div>

<h2>Access Your Portal</h2>

<p>You can now log in to your portal dashboard to:</p>

<ul style="margin: 0; padding-left: 20px; color: #374151;">
    <li style="margin-bottom: 8px;">View and update your bio data</li>
    <li style="margin-bottom: 8px;">Manage your documents</li>
    <li style="margin-bottom: 8px;">View performance reviews and appraisals</li>
    <li style="margin-bottom: 8px;">Access HR communications</li>
    <li style="margin-bottom: 0;">Track your employment information</li>
</ul>

@component('emails.components.button', ['url' => $loginUrl])
Login to Portal
@endcomponent

<p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
    <strong>Security Note:</strong> Keep your login credentials confidential. If you need to reset your password, contact your administrator.
</p>

<p>Sincerely,<br>
<strong>Fortress Lenders Administration Team</strong></p>
@endcomponent
