<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Replace 'password' with a secure password
            'role' => 'admin', // Assuming you have a 'role' column in your users table
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
