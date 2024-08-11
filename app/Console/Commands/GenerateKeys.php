<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Crypto\Rsa\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Rsa\KeyPair;

class GenerateKeys extends Command
{
    protected $signature = 'crypto:generate-keys';

    protected $description = 'Generate public and private keys for encrypt and decrypt data';

    public function handle()
    {
        Storage::makeDirectory('keys');

        $privateKeyPath = storage_path('app/keys/private.key') ;
        $publicKeyPath = storage_path('app/keys/public.key');

        (new KeyPair())->generate($privateKeyPath, $publicKeyPath);

        $files = Storage::allFiles('keys/');

        if (! file_exists($publicKeyPath)) {
            throw FileDoesNotExist::make($publicKeyPath);
        }

        $publicKey = file_get_contents($publicKeyPath);

        $this->info(sprintf('Crypto keys generated successfully in %s and %s', storage_path('app/keys/private.key'), storage_path('app/keys/public.key')));
        $this->info(sprintf('%s and %s', $files[0], $files[1]));
        $this->info(sprintf('%s', $publicKey));
    }
}
