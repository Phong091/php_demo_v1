<?php

namespace App\Services;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(string $email, string $password): void;

    public function authenticate(Request $request, string $email, string $password, bool $remember): ?object;

    public function getCurrentUser(int $userId): ?object;

    public function updateProfile(int $userId, ?string $name, $birthday): void;

    public function createResetTokenAndSendMail(string $email): void;

    public function resetPassword(string $token, string $newPassword): void;
}


