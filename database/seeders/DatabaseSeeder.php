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
        User::query()
            ->firstOrNew(['email' => 'demo@example.com'])
            ->forceFill([
                'name' => 'Demo (Xem thử)',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_readonly' => true,
            ])
            ->save();

        $this->call(ProductSeeder::class);
    }
}
