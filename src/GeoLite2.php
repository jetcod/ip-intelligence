<?php

namespace Jetcod\IpIntelligence;

use GeoIp2\Database\Reader;
use Jetcod\IpIntelligence\Exceptions\DatabaseNotFoundException;

class GeoLite2
{
    public const DB_COUNTRY = 'country';
    public const DB_CITY    = 'city';
    public const DB_ASN     = 'asn';

    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function load(string $dbName): Reader
    {
        $config = $this->validatedConfig($dbName);

        return $this->buildDbReader($config['databases'][$dbName]);
    }

    protected function buildDbReader(string $name): Reader
    {
        return new Reader($name);
    }

    protected function validatedConfig(string $dbName): array
    {
        $validKeys = [self::DB_COUNTRY, self::DB_CITY, self::DB_ASN];

        if (!isset($this->config['databases']) || !is_array($this->config['databases'])) {
            throw new \InvalidArgumentException('The config has missing key `databases`.');
        }

        if (!in_array($dbName, $validKeys)) {
            throw new \InvalidArgumentException(sprintf('Database key `%s` is invalid.', $dbName));
        }

        if (!isset($this->config['databases'][$dbName])) {
            throw new \InvalidArgumentException(sprintf('The key %s is expected in configuration array.', $dbName));
        }

        if (!is_file($this->config['databases'][$dbName])) {
            throw new DatabaseNotFoundException(sprintf('%s database file does not exist.', $dbName));
        }

        return $this->config;
    }
}
