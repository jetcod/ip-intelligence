<?php

namespace Jetcod\IpIntelligence\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'IpIntelligence:data-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install GeoLite2 and Cldr databases.';

    public function handle()
    {
        $databases = [
            'GeoLite2-City.mmdb',
            'GeoLite2-Country.mmdb',
            'GeoLite2-ASN.mmdb',
        ];

        foreach ($databases as $db) {
            $key   = $this->ask(sprintf('Enter the the path to %s file:', $db));
            $value = $this->ask('Enter the new value for the key');

            if (File::exists(base_path('.env'))) {
                File::put(base_path('.env'), str_replace(
                    $key . '=' . env($key),
                    $key . '=' . $value,
                    File::get(base_path('.env'))
                ));

                $this->info("{$key} value set to {$value} in .env file");
            } else {
                $this->error('.env file not found.');
            }
        }
    }
}
