.. _how_to_install

Installation
************

Using Composer
==============

Start by installing the IP-Intelligence package via Composer. Run the following command in your terminal:

.. code-block:: sh

    composer require jetcod/ip-intelligence

This will fetch and install the necessary package files.


Configuration
=============

Laravel project
---------------

IP-Intelligence requires configuration to work effectively. This library offers an artisan command tailored for Laravel projects, streamlining the installation and configuration of necessary databases. To kickstart this process, simply execute the following artisan command:

.. code-block:: sh

    php artisan IpIntelligence:data-install


Throughout this setup, you will be prompted to specify the paths to the Maxmind databases, and the associated environment variables will be automatically configured. 

Non-Laravel project
-------------------

If your project is not based on the Laravel framework, you can integrate the cldr-core package by following these steps:

.. code-block:: sh

    npm install cldr-core

You will then need to configure the environment attributes and specify the paths to the database files manually. Your .env file should resemble the following:

.. code-block:: ini

    # Paths to Maxmind DBs
    MAXMIND_DB_CITY="/path/to/GeoLite2-City.mmdb"
    MAXMIND_DB_COUNTRY="/path/to/GeoIP/GeoLite2-Country.mmdb"
    MAXMIND_DB_ASN="/path/to/GeoIP/GeoLite2-ASN.mmdb"

    # Paths to CLDR datasets
    CLDR_DATA_TERRITORYINFO="/path/to/territoryInfo.json"
