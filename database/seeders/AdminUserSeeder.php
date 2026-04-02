<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada, jika belum buat
        $admin = User::where('email', 'admin@gmail.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // Menambahkan role admin
            ]);

            $this->command->info('Admin user created successfully with role admin.');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}