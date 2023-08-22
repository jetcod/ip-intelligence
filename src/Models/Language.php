<?php

namespace Jetcod\IpIntelligence\Models;

use Jetcod\IpIntelligence\Exceptions\LanguageNotFoundException;
use Symfony\Component\Dotenv\Dotenv;

class Language
{
    /**
     * @var array
     */
    protected $territoryInfo;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var array
     */
    protected $languages = [];

    /**
     * @var array
     */
    protected $officialLanguages = [];

    public function __construct(string $countryCode)
    {
        $this->switchCountry($countryCode);
    }

    /**
     * Set country code.
     */
    public function switchCountry(string $countryCode): self
    {
        $this->countryCode = strtoupper($countryCode);

        return $this->initialize();
    }

    /**
     * Returns all languages of the set country.
     */
    public function all(): array
    {
        return $this->languages;
    }

    /**
     * Returns official and de facto official languages.
     */
    public function officials(): array
    {
        return $this->officialLanguages;
    }

    /**
     * Returns locale of the language.
     */
    public function locale(): ?string
    {
        if (count($this->officials()) < 1) {
            return null;
        }

        return \Locale::composeLocale([
            'language' => $this->officials()[0],
            'region'   => $this->countryCode,
        ]);
    }

    /**
     * Initialize language class.
     */
    public function initialize(): self
    {
        $territoryInfo = $this->loadCldrDataSet();

        foreach ($territoryInfo as $lang => $values) {
            if (isset($values['_officialStatus'])
            && ('official' == $values['_officialStatus'] || 'de_facto_official' == $values['_officialStatus'])) {
                $this->officialLanguages[] = $lang;
            }
            $this->languages[] = $lang;
        }

        return $this;
    }

    /**
     * Load Cldr data set.
     *
     * @throws LanguageNotFoundException Throws exception if and invalid country code is set
     */
    protected function loadCldrDataSet(): array
    {
        $dotenv = new Dotenv(true);
        $dotenv->loadEnv(__DIR__ . '/../../.env');

        if (function_exists('config')) {
            $filePath = config('IpIntelligence.cldr.datasets.territoryInfo');
        } elseif (getenv('CLDR_DATA_TERRITORYINFO')) {
            $filePath = getenv('CLDR_DATA_TERRITORYINFO');
        } else {
            throw new \RuntimeException('Could not find CLDR data file.');
        }

        $territoryInfo = json_decode(file_get_contents($filePath), true);
        if (JSON_ERROR_NONE != json_last_error()
            || !isset($territoryInfo['supplemental']['territoryInfo'][$this->countryCode]['languagePopulation'])) {
            throw new LanguageNotFoundException("There is no language associated with country code {$this->countryCode}.");
        }

        return $territoryInfo['supplemental']['territoryInfo'][$this->countryCode]['languagePopulation'];
    }
}
