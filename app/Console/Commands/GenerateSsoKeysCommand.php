<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSsoKeysCommand extends Command
{
    protected $signature = 'sso:generate-keys {--force : Overwrite existing keys}';

    protected $description = 'Generate the RS256 keypair used to sign SSO handoff tokens (config/sso.php)';

    public function handle(): int
    {
        $privateKeyPath = config('sso.private_key_path');
        $publicKeyPath = config('sso.public_key_path');

        if (! $this->option('force') && File::exists($privateKeyPath) && File::exists($publicKeyPath)) {
            $this->info('SSO keys already exist, skipping. Use --force to overwrite.');

            return self::SUCCESS;
        }

        $resource = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if ($resource === false) {
            $this->error('Failed to generate RSA keypair: '.openssl_error_string());

            return self::FAILURE;
        }

        openssl_pkey_export($resource, $privateKey);
        $publicKey = openssl_pkey_get_details($resource)['key'];

        File::ensureDirectoryExists(dirname($privateKeyPath));
        File::put($privateKeyPath, $privateKey);
        File::put($publicKeyPath, $publicKey);

        $this->info('SSO signing keypair generated.');

        return self::SUCCESS;
    }
}
