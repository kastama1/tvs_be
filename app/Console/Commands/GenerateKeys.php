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

        $keys = (new KeyPair())->generate(storage_path('app/keys/private.key'), storage_path('app/keys/public.key'));

        $this->info(sprintf('Crypto keys generated successfully in %s and %s', storage_path('app/keys/private.key'), storage_path('app/keys/public.key')));
        $this->info(sprintf('%s and %s', $keys[0], $keys[1]));
    }
}
