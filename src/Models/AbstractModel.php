<?php

namespace Jetcod\IpIntelligence\Models;

use Symfony\Component\Dotenv\Dotenv;

abstract class AbstractModel
{
    /**
     * Load Cldr data set.
     */
    abstract protected function loadData();

    /**
     * Read data file content.
     *
     * @return mixed
     */
    protected function readFile(string $name)
    {
        $dotenv = new Dotenv('1');
        $dotenv->load($this->root() . DIRECTORY_SEPARATOR . '.env');

        if (function_exists('config')) {
            $filePath = rtrim(config('IpIntelligence.cldr.path'), DIRECTORY_SEPARATOR);
        } else {
            $filePath = getenv('CLDR_DATA_LIBRARY') ?? $this->root() . DIRECTORY_SEPARATOR . 'node_modules/cldr-core';
        }
        $filePath .= DIRECTORY_SEPARATOR . 'supplemental' . DIRECTORY_SEPARATOR . $name;

        if (!is_file($filePath)) {
            throw new \RuntimeException('Could not find CLDR data file. Please check the environment variable CLDR_DATA_TERRITORYINFO and update the path.');
        }

        return file_get_contents($filePath);
    }

    /**
     * Get project root.
     */
    protected function root(): string
    {
        if (function_exists('base_path')) {
            return base_path();
        }

        return realpath(__DIR__ . '/../../../../..');
    }
}
