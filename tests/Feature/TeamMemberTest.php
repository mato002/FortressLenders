<?php

namespace Tests\Feature;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_team_members(): void
    {
        TeamMember::factory()->create(['name' => 'Test Leader', 'is_active' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Test Leader');
    }

    public function test_admin_can_create_team_member(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.team-members.store'), [
            'name' => 'Jane Doe',
            'role' => 'Finance Lead',
            'email' => 'jane@example.com',
            'display_order' => 1,
            'is_active' => true,
            'photo' => UploadedFile::fake()->image('headshot.jpg'),
        ]);

        $response->assertRedirect(route('admin.team-members.index'));
        $this->assertDatabaseHas('team_members', [
            'name' => 'Jane Doe',
            'role' => 'Finance Lead',
        ]);
    }
}







