# IP-Intelligence

[![Actions Status](https://github.com/jetcod/ip-intelligence/actions/workflows/php.yml/badge.svg?style=for-the-badge&label=%3Cb%3EBuild%3C/b%3E)](https://github.com/jetcod/ip-intelligence/actions)


[![Latest Stable Version](https://poser.pugx.org/jetcod/ip-intelligence/v?style=for-the-badge)](https://packagist.org/packages/jetcod/ip-intelligence)
[![License](http://poser.pugx.org/jetcod/ip-intelligence/license?style=for-the-badge)](https://packagist.org/packages/jetcod/ip-intelligence)


## Overview
IP-Intelligence is a versatile PHP library designed for comprehensive IP address intelligence analysis. Powered by the robust MaxMind database and seamlessly integrated with the CLDR (Common Locale Data Repository) package, this library empowers you to extract a wealth of information from an IP address. Whether you need to uncover geolocation data, ASN (Autonomous System Number) information, or even detect the language and locale associated with an IP address, IP-Intelligence provides the essential tools to enrich your data analysis and decision-making processes.

## Requirements
This library requires:

- php7.4 or 8.0+
- Maxmind Db (available at [MaxMind Website](https://dev.maxmind.com/geoip))

Once you installed Maxmind Db you will have 3 database files as follows:

- GeoLite2-Country.mmdb
- GeoLite2-City.mmdb
- GeoLite2-ASN.mmdb

Keep the path to each file for further installation.

For comprehensive guidance on the installation procedure and requirements, kindly consult the [documentation website](https://jetcod.github.io/ip-intelligence/requirements.html).

## Installation Guide

### Step1: Composer Installation

Start by installing the IP-Intelligence package via Composer. Run the following command in your terminal:

```bash
composer require jetcod/ip-intelligence
```

This will fetch and install the necessary package files.

### Step 2: Configuration 
IP-Intelligence requires configuration to work effectively. This library offers an artisan command tailored for Laravel projects, streamlining the installation and configuration of necessary databases. To kickstart this process, simply execute the following artisan command:

```bash
php artisan IpIntelligence:data-install
```

Throughout this setup, you will be prompted to specify the paths to the Maxmind databases, and the associated environment variables will be automatically configured. 

If your project falls outside the Laravel framework, you should incorporate the `cldr-core` package by executing:

```bash
npm install cldr-core
```

Subsequently, make sure to define these variables within your `.env` file:

## Usage

### IP Lookup
The primary functionality of this library is to perform IP address lookups. You can obtain various details about an IP address using the ip() method. Here's an example of how to use it:


```php
<?php

namespace App\Http\Controllers;

use Jetcod\IpIntelligence\GeoIpLookup;

class TestController
{
    public function __invoke(GeoIpLookup $lookup)
    {
        $address = '206.47.249.128';

        try {
            $ip = $lookup->ip($address);

            // Retrieve Language Information
            $officialLanguages = $ip->language()->officials();
            // Returns an array of official languages spoken in the region, e.g., ['en', 'fr']

            $allLanguages = $ip->language()->all();
            // Returns an array of all languages spoken in the region, e.g., ['ar', 'atj', 'bla', 'bn', ...]

            $locale = $ip->language()->locale;
            // Returns the locale for the region, e.g., 'en_CA'

            // Retrieve City Information
            $cityName = $ip->city()->name;
            // Returns the name of the city, e.g., 'Toronto'

            // Retrieve Country Information
            $countryName = $ip->country()->name;
            // Returns the name of the country, e.g., 'Canada'

            dd(
                "IP Address: $address",
                "Official Languages: " . implode(', ', $officialLanguages),
                "All Languages: " . implode(', ', $allLanguages),
                "Locale: $locale",
                "City Name: $cityName",
                "Country Name: $countryName"
            );

        } catch (\Jetcod\IpIntelligence\Exceptions\InvalidIpAddressException $e) {
            // Handle the case where the provided IP address is invalid.
            echo $e->getMessage();
        } catch (\GeoIp2\Exception\AddressNotFoundException $e) {
            // Handle the case where the IP address is not found in the database.
            echo $e->getMessage();
        }
    }
}
```

This code demonstrates how to initialize the IpLookup class, perform an IP lookup, and retrieve information about the given IP address, including language, city, and country details. Be sure to handle exceptions as shown in the code to gracefully manage errors that may arise during the lookup process.

### Language

The `Language` class is a component of the Jetcod IP Intelligence library and is designed to provide information about languages spoken in a specific country based on CLDR (Common Locale Data Repository) data. This class allows you to retrieve details about the languages, official languages, and the locale associated with a given country code.

Here is the usage example:

```php
use Jetcod\IpIntelligence\Models\Language;
use Jetcod\IpIntelligence\Exceptions\LanguageNotFoundException;
use Symfony\Component\Dotenv\Dotenv;

// Create a Language instance for the United States ('US')
$language = new Language('US');

try {
    // Retrieve all languages spoken in the United States
    $allLanguages = $language->all();

    // Retrieve official and de facto official languages
    $officialLanguages = $language->officials();

    // Retrieve the locale for the first official language
    $locale = $language->locale();

    // Print the retrieved data
    print_r($allLanguages);
    print_r($officialLanguages);
    
    echo "Locale: $locale";
} catch (LanguageNotFoundException $e) {
    echo $e->getMessage();
}
```

This usage example demonstrates how to create a Language instance, switch the country, and retrieve language information for that country. It also handles exceptions that may occur during the process.

## Contributing
If you would like to contribute to this library, please fork the repository and submit a pull request. We welcome bug fixes, feature requests, and other contributions.

## License
This library is released under the MIT License. Please see the LICENSE file for more information.