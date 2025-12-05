<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedAdminUser();
        $this->call([
            ProductSeeder::class,
            TeamMemberSeeder::class,
            BranchSeeder::class,
            JobPostSeeder::class,
        ]);
    }

    protected function seedAdminUser(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@fortresslenders.com');
        $adminName = env('ADMIN_NAME', 'Fortress Admin');
        $adminPassword = env('ADMIN_PASSWORD', 'ChangeMe123!');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );
    }
}
