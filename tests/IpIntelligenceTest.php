<?php

namespace Jetcod\IpIntelligence\Tests;

use GeoIp2\Model\Asn;
use GeoIp2\Record\City as CityRecord;
use GeoIp2\Record\Country as CountryRecord;
use GeoIp2\Record\Postal as PostalRecord;
use Jetcod\IpIntelligence\Exceptions\InvalidIpAddressException;
use Jetcod\IpIntelligence\GeoIpLookup;
use Jetcod\IpIntelligence\GeoLite2;
use Mockery as m;

/**
 * @internal
 *
 * @coversNothing
 */
class IpIntelligenceTest extends TestCase
{
    public function testIpWithValidIpAddressReturnsSelf()
    {
        $ipAddress      = '8.8.8.8';
        $mockedGeoLite2 = m::mock(GeoLite2::class);
        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);

        $this->assertSame($ipIntelligence, $ipIntelligence->ip($ipAddress));
    }

    public function testIpWithInvalidIpAddressThrowsException()
    {
        $ipAddress      = 'invalid_ip_address';
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(InvalidIpAddressException::class);
        $this->expectExceptionMessage("The address {$ipAddress} is not a valid IP address.");

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $ipIntelligence->ip($ipAddress);
    }

    public function testCityReturnsCityRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = CityRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_CITY, $expectedRecord);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $record         = $ipIntelligence->ip($ipAddress)->city();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testCityWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $ipIntelligence->city();
    }

    public function testCountryReturnsCountryRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = CountryRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_COUNTRY, CountryRecord::class);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $record         = $ipIntelligence->ip($ipAddress)->country();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testCountryWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $ipIntelligence->country();
    }

    public function testPostalReturnsPostalRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = PostalRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_CITY, $expectedRecord);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $record         = $ipIntelligence->ip($ipAddress)->postal();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testPostalWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $ipIntelligence->postal();
    }

    public function testAsnReturnsAsnModel()
    {
        $ipAddress      = '8.8.8.8';
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_ASN);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $record         = $ipIntelligence->ip($ipAddress)->asn();

        $this->assertInstanceOf(Asn::class, $record);
    }

    public function testAsnWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $ipIntelligence = new GeoIpLookup($mockedGeoLite2);
        $ipIntelligence->asn();
    }
}
