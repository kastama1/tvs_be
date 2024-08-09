<?php

namespace App\Services;

use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class CryptoService
{
    public function getHash(string $previousHash, string $data, string $createdAt): string
    {
        return hash('sha256', $previousHash.$data.$createdAt);
    }

    public function encrypt(string $data): string
    {
        $publicKey = PublicKey::fromFile(storage_path('app/keys/public.key'));

        return $publicKey->encrypt($data);
    }

    public function decrypt(string $encryptedData): string
    {
        $privateKey = PrivateKey::fromFile(storage_path('app/keys/private.key'));

        return $privateKey->decrypt($encryptedData);
    }
}
