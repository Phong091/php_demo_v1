<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function __construct(
        private readonly string $secret,
        private readonly int $ttlMinutes,
        private readonly string $issuer,
        private readonly string $audience,
    ) {
    }

    public static function fromConfig(): self
    {
        $config = config('jwt');
        return new self(
            $config['secret'],
            (int)$config['ttl_minutes'],
            (string)$config['issuer'],
            (string)$config['audience']
        );
    }

    public function issueToken(int $userId, int $role, ?int $ttlMinutesOverride = null): string
    {
        $now = time();
        $ttl = $ttlMinutesOverride ?? $this->ttlMinutes;
        $exp = $now + ($ttl * 60);
        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $exp,
            'sub' => $userId,
            'role' => $role,
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function verifyAndDecode(string $jwt): object
    {
        return JWT::decode($jwt, new Key($this->secret, 'HS256'));
    }
}



