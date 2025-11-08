<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthService implements AuthServiceInterface
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function register(string $email, string $password): void
    {
        $salt = bin2hex(random_bytes(16));
        $hashed = md5($password . $salt);
        $this->users->create($email, $hashed, $salt);
    }

    public function authenticate(Request $request, string $email, string $password, bool $remember): ?object
    {
        $user = $this->users->findByEmail($email);
        if (!$user) return null;
        if (md5($password . $user->salt) !== $user->password) return null;
        return $user;
    }

    public function getCurrentUser(int $userId): ?object
    {
        return $this->users->findById($userId);
    }

    public function updateProfile(int $userId, ?string $name, $birthday): void
    {
        $this->users->updateProfile($userId, $name, $birthday);
    }

    public function createResetTokenAndSendMail(string $email): void
    {
        $user = $this->users->findByEmail($email);
        if (!$user) {
            throw new \InvalidArgumentException('Email không tồn tại');
        }
        $token = Str::random(60);
        $this->users->setResetToken($user->id, $token);

        $resetLink = url("/reset-password/{$token}");
        Mail::raw("Click để reset password: $resetLink", function ($message) use ($email) {
            $message->to($email)->subject('Reset Password');
        });
    }

    public function resetPassword(string $token, string $newPassword): void
    {
        $user = $this->users->findByResetToken($token);
        if (!$user) {
            throw new \InvalidArgumentException('Link không hợp lệ');
        }
        $passwordHash = md5($newPassword . $user->salt);
        $this->users->updatePasswordAndClearToken($user->id, $passwordHash);
    }
}


