<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'ulid' => (string) Str::ulid(),
                'name' => 'System Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'status' => 'active',
            ],
        );

        $admin->syncRoles(['Super Admin']);
    }
}
