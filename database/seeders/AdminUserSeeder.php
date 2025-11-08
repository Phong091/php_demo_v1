<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'admin@example.com';
        $exists = DB::table('users')->where('email', $email)->exists();
        if ($exists) {
            return;
        }

        $salt = bin2hex(random_bytes(16));
        $password = 'Admin@1234';
        $passwordHash = md5($password . $salt);

        DB::table('users')->insert([
            'email' => $email,
            'password' => $passwordHash,
            'salt' => $salt,
            'name' => 'Administrator',
            'birthday' => null,
            'token' => null,
            'role' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}



