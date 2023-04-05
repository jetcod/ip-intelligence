<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IpIntelligence Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure settings for IpIntelligence.
    |
    */

    'GeoLite2' => [
        'datasets' => [
            'country' => env('MAXMIND_DB_COUNTRY', '/usr/share/GeoIP/GeoLite2-Country.mmdb'),
            'city'    => env('MAXMIND_DB_CITY', '/usr/share/GeoIP/GeoLite2-City.mmdb'),
            'asn'     => env('MAXMIND_DB_ASN', '/usr/share/GeoIP/GeoLite2-ASN.mmdb'),
        ],
    ],

    'cldr' => [
        'datasets' => [
            'territoryInfo' => env('CLDR_DATA_TERRITORYINFO'),
        ],
    ],
];
