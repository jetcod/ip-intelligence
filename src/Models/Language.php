<?php

namespace Jetcod\IpIntelligence\Models;

use Jetcod\IpIntelligence\Exceptions\LanguageNotFoundException;

/**
 * The Language class serves as a model for language-related data.
 *
 * @property null|string $locale    The locale corresponding to the language recognized in the country.
 * @property array       $all       An array containing all languages spoken within the specified country.
 * @property array       $officials An array listing the official languages spoken in the specified country.
 */
class Language extends AbstractModel
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
    protected function all(): array
    {
        return $this->languages;
    }

    /**
     * Returns official and de facto official languages.
     */
    protected function officials(): array
    {
        return $this->officialLanguages;
    }

    /**
     * Returns locale of the language.
     */
    protected function locale(): ?string
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
    protected function initialize(): self
    {
        $territoryInfo = $this->loadData();

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
     * @throws \RuntimeException         Throws exception if CLDR data file is not readable
     */
    protected function loadData(): array
    {
        if (false !== $fileContent = $this->readFile('territoryInfo.json')) {
            $territoryInfo = json_decode($fileContent, true);
            if (JSON_ERROR_NONE != json_last_error()
            || !isset($territoryInfo['supplemental']['territoryInfo'][$this->countryCode]['languagePopulation'])) {
                throw new LanguageNotFoundException("There is no language associated with country code {$this->countryCode}.");
            }
        } else {
            throw new \RuntimeException('Could not read CLDR data file. Please check if the file is readable.');
        }

        return $territoryInfo['supplemental']['territoryInfo'][$this->countryCode]['languagePopulation'];
    }
}
