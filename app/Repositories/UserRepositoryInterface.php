<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?object;

    public function findById(int $id): ?object;

    public function create(string $email, string $passwordHash, string $salt): int;

    public function updateProfile(int $id, ?string $name, $birthday): void;

    public function listAll(): array;

    public function setResetToken(int $id, string $token): void;

    public function findByResetToken(string $token): ?object;

    public function updatePasswordAndClearToken(int $id, string $passwordHash): void;
}


