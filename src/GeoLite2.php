<?php

namespace Jetcod\IpIntelligence;

use GeoIp2\Database\Reader;
use Jetcod\IpIntelligence\Exceptions\DatabaseNotFoundException;

class GeoLite2
{
    public const DB_COUNTRY = 'country';
    public const DB_CITY    = 'city';
    public const DB_ASN     = 'asn';

    /**
     * Configuration parameters.
     *
     * array[
     *     'datasets' => array[
     *         'country' => string, // File path for the country database
     *         'city'    => string, // File path for the city database
     *         'asn'     => string, // File path for the ASN database
     *     ]
     * ]
     *
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Loads a reader for the GeoIP2 database format.
     */
    public function load(string $dbName): Reader
    {
        $config = $this->validatedConfig($dbName);

        return $this->buildDbReader($config['datasets'][$dbName]);
    }

    /**
     * Instanciates a reader for the GeoIP2 database format.
     *
     * @param string $filename the path to the GeoIP2 database file
     */
    protected function buildDbReader(string $filename): Reader
    {
        return new Reader($filename);
    }

    /**
     * Validates the configuration array for a given database name.
     *
     * @param string $dbName the name of the database to validate the configuration for
     *
     * @return array the validated configuration array
     *
     * @throws \InvalidArgumentException if the configuration array has a missing 'databases' key, if the specified database name is invalid, or if the configuration array is missing the specified database name as a key
     * @throws DatabaseNotFoundException if the specified database file does not exist
     */
    protected function validatedConfig(string $dbName): array
    {
        $validKeys = [self::DB_COUNTRY, self::DB_CITY, self::DB_ASN];

        if (!isset($this->config['datasets']) || !is_array($this->config['datasets'])) {
            throw new \InvalidArgumentException('The config has missing key `datasets`.');
        }

        if (!in_array($dbName, $validKeys)) {
            throw new \InvalidArgumentException(sprintf('Database key `%s` is invalid.', $dbName));
        }

        if (!isset($this->config['datasets'][$dbName])) {
            throw new \InvalidArgumentException(sprintf('The key %s is expected in configuration array.', $dbName));
        }

        if (!is_file($this->config['datasets'][$dbName])) {
            throw new DatabaseNotFoundException(sprintf('%s database file does not exist.', $dbName));
        }

        return $this->config;
    }
}
