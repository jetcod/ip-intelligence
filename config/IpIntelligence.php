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
            'country' => env('MAXMIND_DB_COUNTRY'),
            'city'    => env('MAXMIND_DB_CITY'),
            'asn'     => env('MAXMIND_DB_ASN'),
        ],
    ],

    'cldr' => [
        'path' => env('CLDR_DATA_LIBRARY', 'node_modules/cldr-core'),
    ],
];
