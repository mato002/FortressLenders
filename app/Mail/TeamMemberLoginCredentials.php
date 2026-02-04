<?php

namespace App\Mail;

use App\Models\TeamMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamMemberLoginCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public TeamMember $teamMember;
    public string $password;
    public string $loginUrl;

    public function __construct(TeamMember $teamMember, string $password)
    {
        $this->teamMember = $teamMember;
        $this->password = $password;
        $this->loginUrl = route('login');
    }

    public function build(): self
    {
        return $this
            ->subject('Your Portal Login Credentials - Fortress Lenders')
            ->view('emails.team-member.login-credentials');
    }
}
