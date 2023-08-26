<?php

namespace Jetcod\IpIntelligence\Tests;

use GeoIp2\Database\Reader;
use GeoIp2\Model\Asn as AsnModel;
use GeoIp2\Model\City as CityModel;
use GeoIp2\Model\Country as CountryModel;
use GeoIp2\Record\Postal as PostalRecord;
use Jetcod\IpIntelligence\GeoLite2;
use Jetcod\IpIntelligence\Models\Language;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as m;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        m::close();
    }

    protected function createGeoLite2Mock($database, $expectedRecordClass = null)
    {
        $mockedReader = m::mock(Reader::class);

        switch ($database) {
            case GeoLite2::DB_CITY:
                $expectedRecord = m::mock($expectedRecordClass);
                if ($expectedRecord instanceof PostalRecord) {
                    $expectedModel = $this->setProtectedAttribute(m::mock(CityModel::class), 'postal', $expectedRecord);
                } else {
                    $expectedModel = $this->setProtectedAttribute(m::mock(CityModel::class), 'city', $expectedRecord);
                }

                $mockedReader->shouldReceive('city')->andReturn($expectedModel);

                break;

            case GeoLite2::DB_COUNTRY:
                $expectedRecord = m::mock($expectedRecordClass);
                $expectedModel  = $this->setProtectedAttribute(m::mock(CountryModel::class), 'country', $expectedRecord);
                $mockedReader->shouldReceive('country')->andReturn($expectedModel);

                break;

            case GeoLite2::DB_ASN:
                $mockedReader->shouldReceive('asn')->andReturn(m::mock(AsnModel::class));

                break;

            default:
                $mockedReader = null;

                break;
        }

        $mockedGeoLite2 = m::mock(GeoLite2::class);
        $mockedGeoLite2->shouldReceive('load')->with($database)->andReturn($mockedReader);

        return $mockedGeoLite2;
    }

    protected function setProtectedAttribute($object, $attr, $val)
    {
        $reflectionClass = new \ReflectionClass($object);
        $property        = $reflectionClass->getProperty($attr);
        $property->setAccessible(true);

        $property->setValue($object, $val);

        return $object;
    }

    protected function getProtectedAttribute($object, $attr)
    {
        $reflectionClass = new \ReflectionClass($object);
        $property        = $reflectionClass->getProperty($attr);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    protected function createLanguageMock()
    {
        $language = m::mock(Language::class)->makePartial();
        $this->setProtectedAttribute($language, 'countryCode', 'US');
        $language->shouldAllowMockingProtectedMethods();

        return $language;
    }
}
