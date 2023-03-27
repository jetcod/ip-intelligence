<?php

namespace Jetcod\IpIntelligence\Tests;

use GeoIp2\Model\Asn;
use GeoIp2\Record\City as CityRecord;
use GeoIp2\Record\Country as CountryRecord;
use GeoIp2\Record\Postal as PostalRecord;
use Jetcod\IpIntelligence\Exceptions\InvalidIpAddressException;
use Jetcod\IpIntelligence\GeoIpLookup;
use Jetcod\IpIntelligence\GeoLite2;
use Jetcod\IpIntelligence\Models\Language;
use Mockery as m;

/**
 * @internal
 *
 * @coversNothing
 */
class GeoIpLookupTest extends TestCase
{
    public function testIpWithValidIpAddressReturnsSelf()
    {
        $ipAddress      = '8.8.8.8';
        $mockedGeoLite2 = m::mock(GeoLite2::class);
        $lookup         = new GeoIpLookup($mockedGeoLite2);

        $this->assertSame($lookup, $lookup->ip($ipAddress));
        $this->assertInstanceOf(GeoIpLookup::class, $lookup->ip($ipAddress));
    }

    public function testIpWithInvalidIpAddressThrowsException()
    {
        $ipAddress      = 'invalid_ip_address';
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(InvalidIpAddressException::class);
        $this->expectExceptionMessage("The address {$ipAddress} is not a valid IP address.");

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $lookup->ip($ipAddress);
    }

    public function testCityReturnsCityRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = CityRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_CITY, $expectedRecord);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $record = $lookup->ip($ipAddress)->city();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testCityWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $lookup->city();
    }

    public function testCountryReturnsCountryRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = CountryRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_COUNTRY, CountryRecord::class);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $record = $lookup->ip($ipAddress)->country();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testCountryWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $lookup->country();
    }

    public function testPostalReturnsPostalRecord()
    {
        $ipAddress      = '8.8.8.8';
        $expectedRecord = PostalRecord::class;
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_CITY, $expectedRecord);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $record = $lookup->ip($ipAddress)->postal();

        $this->assertInstanceOf($expectedRecord, $record);
    }

    public function testPostalWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $lookup->postal();
    }

    public function testAsnReturnsAsnModel()
    {
        $ipAddress      = '8.8.8.8';
        $mockedGeoLite2 = $this->createGeoLite2Mock(GeoLite2::DB_ASN);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $record = $lookup->ip($ipAddress)->asn();

        $this->assertInstanceOf(Asn::class, $record);
    }

    public function testAsnWithoutIpThrowsException()
    {
        $mockedGeoLite2 = m::mock(GeoLite2::class);

        $this->expectException(\BadMethodCallException::class);

        $lookup = new GeoIpLookup($mockedGeoLite2);
        $lookup->asn();
    }

    public function testLanguageReturnsLanguageModel()
    {
        $ipAddress = '8.8.8.8';

        $countryRecord          = m::mock(CountryRecord::class);
        $countryRecord->isoCode = 'US';

        $mockedGeoIpLookup = m::mock(GeoIpLookup::class)->makePartial();
        $mockedGeoIpLookup->shouldReceive('country')->andReturn($countryRecord);

        $language = $mockedGeoIpLookup->ip($ipAddress)->language();

        $this->assertInstanceOf(Language::class, $language);
    }
}
