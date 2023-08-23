<?php

namespace Jetcod\IpIntelligence\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Process\Process;

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
        if ($this->SetGeolite2MmdbPath() && $this->InstallCldrData()) {
            $this->line('');
            $this->info('Setup completed successfully!');

            return;
        }

        $this->error('Setup could not be completed!');
    }

    protected function SetGeolite2MmdbPath(): bool
    {
        $databases = [
            'city'    => ['env' => 'MAXMIND_DB_CITY', 'db' => 'GeoLite2-City.mmdb'],
            'country' => ['env' => 'MAXMIND_DB_COUNTRY', 'db' => 'GeoLite2-Country.mmdb'],
            'asn'     => ['env' => 'MAXMIND_DB_ASN', 'db' => 'GeoLite2-ASN.mmdb'],
        ];

        foreach ($databases as $item) {
            do {
                $value = $this->ask(sprintf('Enter the path to %s file', $item['db']), env($item['env']));
                if (!$validPath = File::exists($value)) {
                    $this->error(sprintf('Could not find the file at %s! Please check the path and try again.', $value));
                }
            } while (!$validPath);

            try {
                $this->updateEnvironmentVariable($item['env'], $value);
            } catch (FileNotFoundException $e) {
                $this->warn($e->getMessage());

                return false;
            }
        }

        return true;
    }

    protected function updateEnvironmentVariable(string $key, string $value)
    {
        if (File::exists(base_path('.env'))) {
            $envFile     = base_path('.env');
            $envContents = File::get($envFile);
            $keyExists   = Str::contains($envContents, $key);

            if ($keyExists) {
                $envContents = str_replace(
                    $key . '=' . env($key),
                    $key . '=' . $value,
                    $envContents
                );
            } else {
                $envContents .= "\n{$key}={$value}";
            }

            File::put($envFile, $envContents);
        } else {
            throw new FileNotFoundException('.env file not found.');
        }
    }

    protected function installCldrData(): bool
    {
        $this->warn('Installing cldr-core package ...');
        $this->line('');

        $command = ['npm', 'install', 'cldr-core', '--progress'];
        $process = new Process($command);
        $process->setWorkingDirectory(base_path())->run(function ($type, $output) {
            if (Process::ERR === $type) {
                // Handle stderr (error) output
                $this->error($output);
            } else {
                // Handle stdout (standard) output
                echo $output;
            }
        });

        // Check if the command was successful
        if ($process->isSuccessful()) {
            try {
                $this->updateEnvironmentVariable('CLDR_DATA_TERRITORYINFO', base_path('node_modules/cldr-core/supplemental/territoryInfo.json'));
                $this->info('CLDR data installed successfully.');

                return true;
            } catch (FileNotFoundException $e) {
                $this->warn($e->getMessage());
            }

            return false;
        }

        // Display the output
        $output = $process->getOutput();
        $this->line($output);

        return false;
    }
}
