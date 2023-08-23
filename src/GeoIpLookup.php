<?php

namespace Jetcod\IpIntelligence;

use GeoIp2\Database\Reader;
use GeoIp2\Model\Asn;
use GeoIp2\Record\City;
use GeoIp2\Record\Country;
use GeoIp2\Record\Postal;
use Jetcod\IpIntelligence\Exceptions\InvalidIpAddressException;
use Jetcod\IpIntelligence\Models\Language;

class GeoIpLookup
{
    /**
     * @var string
     */
    protected $ipAddress;

    /**
     * @var GeoLite2
     */
    private $db;

    public function __construct(GeoLite2 $db)
    {
        $this->db = $db;
    }

    /**
     * Set an IP address.
     *
     * @throws InvalidIpAddressException
     */
    public function ip(string $ipAddress): self
    {
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            throw new InvalidIpAddressException("The address {$ipAddress} is not a valid IP address.");
        }

        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get city record of an identified IP.
     *
     * @throws AddressNotFoundException
     */
    public function city(): City
    {
        $model = $this->reader(GeoLite2::DB_CITY)->city($this->ipAddress);

        return $model->city;
    }

    /**
     * Get country record of an identified IP.
     *
     * @throws AddressNotFoundException
     */
    public function country(): Country
    {
        $model = $this->reader(GeoLite2::DB_COUNTRY)->country($this->ipAddress);

        return $model->country;
    }

    /**
     * Get postal record of an identified IP.
     *
     * @throws AddressNotFoundException
     */
    public function postal(): Postal
    {
        $model = $this->reader(GeoLite2::DB_CITY)->city($this->ipAddress);

        return $model->postal;
    }

    /**
     * Get Geolite2 ASN model of an identified IP.
     *
     * @throws AddressNotFoundException
     */
    public function asn(): Asn
    {
        return $this->reader(GeoLite2::DB_ASN)->asn($this->ipAddress);
    }

    /**
     * Retrive associated language model with the recognized country.
     */
    public function language(): Language
    {
        return new Language($this->country()->isoCode);
    }

    /**
     * Returns and instance of Reader for the GeoIP2 database format.
     */
    protected function reader(string $type): Reader
    {
        return $this->db->load($type);
    }
}
