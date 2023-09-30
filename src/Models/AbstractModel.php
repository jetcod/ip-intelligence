<?php

namespace Jetcod\IpIntelligence\Models;

use Symfony\Component\Dotenv\Dotenv;

abstract class AbstractModel
{
    /**
     * @return mixed
     */
    public function __get(string $attr)
    {
        if (method_exists($this, $attr)) {
            return $this->{$attr}();
        }

        throw new \RuntimeException("Unknown attribute: {$attr}");
    }

    /**
     * Load Cldr data set.
     *
     * @return mixed
     */
    abstract protected function loadData();

    /**
     * Read data file content.
     *
     * @return mixed
     *
     * @throws \RuntimeException If CLDR data file not found
     */
    protected function readFile(string $name)
    {
        $dotenv = new Dotenv('1');
        $dotenv->load($this->root() . DIRECTORY_SEPARATOR . '.env');

        if (function_exists('config')) {
            $filePath = rtrim(config('IpIntelligence.cldr.path'), DIRECTORY_SEPARATOR);
        } else {
            $filePath = getenv('CLDR_DATA_LIBRARY') ?: $this->root() . DIRECTORY_SEPARATOR . 'node_modules/cldr-core';
        }
        $filePath .= DIRECTORY_SEPARATOR . 'supplemental' . DIRECTORY_SEPARATOR . $name;

        if (!is_file($filePath)) {
            throw new \RuntimeException('Could not find CLDR data file. Please check the environment variable CLDR_DATA_TERRITORYINFO and update the path.');
        }

        return file_get_contents($filePath);
    }

    /**
     * Get project root.
     *
     * @throws \RuntimeException If cannot make path to the root of the project
     */
    protected function root(): string
    {
        if (function_exists('base_path')) {
            return base_path();
        }

        $root = realpath(__DIR__ . '/../../../../../');
        if (false === $root) {
            throw new \RuntimeException('Could not recognize project root.');
        }

        return $root;
    }
}
