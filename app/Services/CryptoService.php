<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Spatie\SimpleExcel\SimpleExcelWriter;

class CryptoService
{
    public function getHash(string $previousHash, string $data): string
    {
        return hash('sha256', $previousHash.$data.Carbon::now()->toDateTimeString());
    }

    public function encryptPublic(string $data): string
    {
        $publicKey = PublicKey::fromFile(storage_path('app/keys/public.key'));

        return $publicKey->encrypt($data);
    }

    public function encryptPrivate(string $data): string
    {
        $privateKey = PrivateKey::fromFile(storage_path('app/keys/private.key'));

        return $privateKey->encrypt($data);
    }

    public function decryptPrivate(string $encryptedData): string
    {
        $privateKey = PrivateKey::fromFile(storage_path('app/keys/private.key'));

        return $privateKey->decrypt($encryptedData);
    }

    public function createSignature($data): string
    {
        $signaturePath = public_path('storage/signature.csv');

        SimpleExcelWriter::create($signaturePath)
            ->addHeader(['Signature'])
            ->addRow([
                'Signature' => base64_encode($this->encryptPrivate(hash('sha256', $data))),
            ]);

        return $signaturePath;
    }
}
