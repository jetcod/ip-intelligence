<?php

namespace Jetcod\IpIntelligence\Tests;

use Jetcod\IpIntelligence\Models\Language;

/**
 * @internal
 *
 * @coversNothing
 */
class LanguageModelTest extends TestCase
{
    public function testSwitchCountrySetsCountryCodeAndReturnsSelf()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('initialize')->andReturnSelf();

        $this->assertInstanceOf(Language::class, $language->switchCountry('ca'));
    }

    public function testAllReturnsArrayOfLanguages()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('all')->andReturn([]);

        $this->assertIsArray($language->all());
    }

    public function testOfficialsReturnsArrayOfOfficialLanguages()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('all')->andReturn([]);

        $this->assertIsArray($language->officials());
    }

    public function testLocaleReturnsString()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('officials')->andReturn(['en']);

        $this->assertIsString($locale = $language->locale());
        $this->assertEquals('en_US', $locale);
    }

    public function testLocaleReturnsNullByInvalidLanguageCode()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('officials')->andReturn([]);

        $this->assertNull($language->locale());
    }
}
