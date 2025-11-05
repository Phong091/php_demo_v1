<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class DbUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?object
    {
        return DB::table('users')->where('email', $email)->first() ?: null;
    }

    public function findById(int $id): ?object
    {
        return DB::table('users')->where('id', $id)->first() ?: null;
    }

    public function create(string $email, string $passwordHash, string $salt): int
    {
        return DB::table('users')->insertGetId([
            'email' => $email,
            'password' => $passwordHash,
            'salt' => $salt,
            'name' => '',
            'birthday' => null,
            'token' => null,
        ]);
    }

    public function updateProfile(int $id, ?string $name, $birthday): void
    {
        DB::table('users')->where('id', $id)->update([
            'name' => $name,
            'birthday' => $birthday,
        ]);
    }

    public function setResetToken(int $id, string $token): void
    {
        DB::table('users')->where('id', $id)->update(['token' => $token]);
    }

    public function findByResetToken(string $token): ?object
    {
        return DB::table('users')->where('token', $token)->first() ?: null;
    }

    public function updatePasswordAndClearToken(int $id, string $passwordHash): void
    {
        DB::table('users')->where('id', $id)->update([
            'password' => $passwordHash,
            'token' => null,
        ]);
    }
}


