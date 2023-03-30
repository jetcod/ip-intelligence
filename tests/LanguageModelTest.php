<?php

namespace Jetcod\IpIntelligence\Tests;

use Jetcod\IpIntelligence\Exceptions\LanguageNotFoundException;
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
        $language = new Language('us');
        $language = $language->switchCountry('ca');

        $this->assertInstanceOf(Language::class, $language);
    }

    public function testAllReturnsArrayOfLanguages()
    {
        $language = new Language('us');

        $this->assertIsArray($language->all());
    }

    public function testOfficialsReturnsArrayOfOfficialLanguages()
    {
        $language = new Language('us');

        $this->assertIsArray($language->officials());
    }

    public function testLocaleReturnsCorrectLocale()
    {
        $language = new Language('us');

        $this->assertEquals('en_US', $language->locale());
    }

    public function testLoadCldrDataSetThrowsExceptionForInvalidCountryCode()
    {
        $this->expectException(LanguageNotFoundException::class);

        $language = new Language('invalid');
    }
}
