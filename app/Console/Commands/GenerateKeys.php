<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Crypto\Rsa\KeyPair;

class GenerateKeys extends Command
{
    protected $signature = 'crypto:generate-keys';

    protected $description = 'Generate public and private keys for encrypt and decrypt data';

    public function handle()
    {
        Storage::makeDirectory('keys');

        (new KeyPair())->generate(storage_path('app/keys/private.key'), storage_path('app/keys/public.key'));

        $this->info('Crypto keys generated successfully');
    }
}
