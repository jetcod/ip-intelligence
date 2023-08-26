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
    // Sample data from territoryInfo.json
    protected $territoryInfoArray = [
        'supplemental' => [
            'territoryInfo' => [
                'US' => [
                    '_gdp'               => '19490000000000',
                    '_literacyPercent'   => '99',
                    '_population'        => '332639000',
                    'languagePopulation' => [
                        'cad' => [
                            '_populationPercent' => '0',
                        ],
                        'cho' => [
                            '_populationPercent' => '0.0033',
                        ],
                        'chr' => [
                            '_writingPercent'    => '5',
                            '_populationPercent' => '0.0077',
                        ],
                        'cic' => [
                            '_populationPercent' => '0',
                        ],
                        'dak' => [
                            '_populationPercent' => '0.0059',
                        ],
                        'de' => [
                            '_populationPercent' => '0.47',
                        ],
                        'en' => [
                            '_populationPercent' => '96',
                            '_officialStatus'    => 'de_facto_official',
                        ],
                        'es' => [
                            '_populationPercent' => '9.6',
                            '_officialStatus'    => 'official_regional',
                        ],
                        'esu' => [
                            '_populationPercent' => '0.0063',
                        ],
                        'fil' => [
                            '_populationPercent' => '0.42',
                        ],
                        'fr' => [
                            '_populationPercent' => '0.56',
                        ],
                        'frc' => [
                            '_populationPercent' => '0.0084',
                        ],
                        'haw' => [
                            '_populationPercent' => '0.0089',
                            '_officialStatus'    => 'official_regional',
                        ],
                        'hnj' => [
                            '_populationPercent' => '0.035',
                        ],
                        'ik' => [
                            '_writingPercent'    => '5',
                            '_populationPercent' => '0.0024',
                        ],
                        'io' => [
                            '_populationPercent' => '0',
                        ],
                        'it' => [
                            '_populationPercent' => '0.34',
                        ],
                        'jbo' => [
                            '_populationPercent' => '0',
                        ],
                        'ko' => [
                            '_populationPercent' => '0.3',
                        ],
                        'lkt' => [
                            '_populationPercent' => '0.0025',
                        ],
                        'mus' => [
                            '_populationPercent' => '0.0012',
                        ],
                        'nv' => [
                            '_populationPercent' => '0.05',
                        ],
                        'osa' => [
                            '_populationPercent' => '0',
                        ],
                        'pdc' => [
                            '_populationPercent' => '0.039',
                        ],
                        'ru' => [
                            '_populationPercent' => '0.24',
                        ],
                        'vi' => [
                            '_populationPercent' => '0.34',
                        ],
                        'yi' => [
                            '_populationPercent' => '0.049',
                        ],
                        'zh_Hant' => [
                            '_populationPercent' => '0.69',
                        ],
                    ],
                ],
            ],
        ],
    ];

    public function testLoadCldrDataFromInvalidFileNameThrowsException()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('readFile')->andReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not read CLDR data file. Please check if the file is readable.');

        $language->loadData();
    }

    public function testLoadCldrDataFromNonJsonContentThrowsException()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('readFile')->andReturn('invalid_json_content');

        $this->expectException(LanguageNotFoundException::class);
        $this->expectExceptionMessage('There is no language associated with country code US.');

        $language->loadData();
    }

    public function testLoadCldrDataReturnsArray()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('readFile')->andReturn(json_encode($this->territoryInfoArray));

        $this->assertEquals($language->loadData(), $this->territoryInfoArray['supplemental']['territoryInfo']['US']['languagePopulation']);
    }

    public function testSwitchCountrySetsCountryCodeAndReturnsSelf()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('initialize')->andReturnSelf();

        $this->assertInstanceOf(Language::class, $language->switchCountry('ca'));
    }

    public function testInitializeSetsLanguagesAndReturnsSelf()
    {
        $language = $this->createLanguageMock();
        $language->shouldReceive('loadData')->andReturn($this->territoryInfoArray['supplemental']['territoryInfo']['US']['languagePopulation']);
        $languageCodes = array_keys($this->territoryInfoArray['supplemental']['territoryInfo']['US']['languagePopulation']);

        $this->assertInstanceOf(Language::class, $language->initialize());
        $this->assertEquals($languageCodes, $this->getProtectedAttribute($language, 'languages'));
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
