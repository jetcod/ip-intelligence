<?php

namespace Jetcod\IpIntelligence\Tests;

use GeoIp2\Database\Reader;
use Jetcod\IpIntelligence\Exceptions\DatabaseNotFoundException;
use Jetcod\IpIntelligence\GeoLite2;
use Mockery as m;

/**
 * @internal
 *
 * @coversNothing
 */
class DatabaseReaderTest extends TestCase
{
    protected $validConfig = [
        'databases' => [
            GeoLite2::DB_COUNTRY => __DIR__ . '/data/GeoIP2-Country.mmdb',
            GeoLite2::DB_CITY    => __DIR__ . '/data/GeoIP2-City.mmdb',
            GeoLite2::DB_ASN     => __DIR__ . '/data/GeoLite2-ASN.mmdb',
        ],
    ];

    public function testLoadWithValidDbNameReturnsReaderInstance()
    {
        $mockedDbReader = m::mock(Reader::class);
        $mockedGeoLite2 = m::mock(GeoLite2::class, [$this->validConfig])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
        ;
        $mockedGeoLite2->shouldReceive('validatedConfig')->andReturn($this->validConfig);
        $mockedGeoLite2->shouldReceive('buildDbReader')
            ->once()
            ->andReturn($mockedDbReader)
        ;

        $reader = $mockedGeoLite2->load(GeoLite2::DB_COUNTRY);
        $this->assertInstanceOf(Reader::class, $reader);
    }

    public function testLoadWithInvalidDbNameThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Database key `invalid_db_name` is invalid.');

        $geoLite2 = new GeoLite2($this->validConfig);

        $geoLite2->load('invalid_db_name');
    }

    public function testLoadWithMissingDbNameKeyThrowsException()
    {
        $config = $this->validConfig;
        unset($config['databases'][GeoLite2::DB_ASN]);  // Remove a valid key

        $geoLite2 = new GeoLite2($config);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The key %s is expected in configuration array.', GeoLite2::DB_ASN));

        $geoLite2->load(GeoLite2::DB_ASN);
    }

    public function testLoadInvalidDbNameKeyThrowsException()
    {
        $geoLite2 = new GeoLite2($this->validConfig);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Database key `invalid_db_key` is invalid.');

        $geoLite2->load('invalid_db_key');
    }

    public function testLoadWithNonExistentDbFileThrowsException()
    {
        $geoLite2 = new GeoLite2($this->validConfig);

        $this->expectException(DatabaseNotFoundException::class);
        $this->expectExceptionMessage(sprintf('%s database file does not exist.', GeoLite2::DB_ASN));

        $geoLite2->load(GeoLite2::DB_ASN);
    }

    private function callPrivateMethod($object, $methodName)
    {
        $reflectionClass  = new \ReflectionClass($object);
        $reflectionMethod = $reflectionClass->getMethod($methodName);
        $reflectionMethod->setAccessible(true);

        $params = array_slice(func_get_args(), 2); // get all the parameters after $methodName

        return $reflectionMethod->invokeArgs($object, $params);
    }
}
