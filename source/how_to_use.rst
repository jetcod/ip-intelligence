.. _how_to_use

How to Use?
***********


The ``jetcod/ip-intelligence`` package provides a comprehensive solution for performing IP address lookups and obtaining valuable geographical and language-related information. This documentation outlines the available methods and their functionality.


IP Lookup
=========


Set IP Address
--------------

The ``ip()`` method is employed to instantiate the ``GeoIpLookup`` object with a valid IP address. Subsequently, you can utilize additional methods to retrieve valuable information associated with the IP address.

.. code-block:: php
    :caption: example: 

    <?php

    $address = '206.47.249.128';
    $ip = $lookup->ip($address);


.. note:: 
   The code example also includes error handling for potential exceptions:

   - InvalidIpAddressException: This exception is thrown when the provided IP address is invalid.
   - AddressNotFoundException: This exception is thrown when the IP address is not found in the database.


Country
-------

The ``country()`` method allows you to retrieve various country-related details associated with the IP address.

.. code-block:: php
    :caption: example: 

    <?php

    $countryName = $ip->country()->name;
    // Returns the name of the country, e.g., 'Canada'


.. table::
   :width: 100%
   :align: center

   +------------+---------------------------------------------------------------------------------+
   | Attributes | Description                                                                     |
   +============+=================================================================================+
   | name       | The name of the country based on the locale extracted from the given IP address |
   +------------+---------------------------------------------------------------------------------+
   | names      | An array map where the keys are locale codes and the values are names           |
   +------------+---------------------------------------------------------------------------------+
   | isoCode    | The two-character ISO 3166-1 alpha code for the country.                        |
   +------------+---------------------------------------------------------------------------------+


City
----

The ``city()`` method allows you to retrieve various city-related details associated with the IP address.

.. code-block:: php
    :caption: example:

    <?php

    $cityName = $ip->city()->name;
    // Returns the name of the city, e.g., 'Toronto'


.. table::
   :width: 100%
   :align: center

   +------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | Attributes | Description                                                                                                                                                                |
   +============+============================================================================================================================================================================+
   | confidence | A value from 0-100 indicating MaxMind confidence that the city is correct. This attribute is only available from the Insights service and the GeoIP2 Enterprise database   |
   +------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | geonameId  | The GeoName ID for the city. This attribute is returned by all location services and databases.                                                                            |
   +------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | name       | An array map where the keys are locale codes and the values are names                                                                                                      |
   +------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | names      | An array map where the keys are locale codes and the values are names                                                                                                      |
   +------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------+


Postal
------

The ``postal()`` method allows you to retrieve various postal-related details associated with the IP address.

.. code-block:: php
    :caption: example:

    <?php

    $postalCode = $ip->postal()->code;


.. table::
   :width: 100%
   :align: center

   +------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | Attributes | Descriptions                                                                                                                                                                                                                             |
   +============+==========================================================================================================================================================================================================================================+
   | code       | The postal code of the location. Postal codes are not available for all countries. In some countries, this will only contain part of the postal code. This attribute is returned by all location databases and services besides Country. |
   +------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | confidence | A value from 0-100 indicating MaxMind confidence that the postal code is correct. This attribute is only available from the Insights service and the GeoIP2 Enterprise database.                                                         |
   +------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+


Asn
---

The ``asn()`` method looks up the autonomous system number and autonomous system organization associated with IPv4 and IPv6 addresses.

.. code-block:: php
    :caption: example:

    <?php

    $asn = $ip->asn()->code;


.. table::
   :width: 100%
   :align: center

   +------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | Attributes                   | Descriptions                                                                                                                                                        |
   +==============================+=====================================================================================================================================================================+
   | cautonomousSystemNumberode   | The autonomous system number associated with the IP address.                                                                                                        |
   +------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | autonomousSystemOrganization | The organization associated with the registered autonomous system number for the IP address.                                                                        |
   +------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | ipAddress                    | The IP address that the data in the model is for.                                                                                                                   |
   +------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | network                      | The network in CIDR notation associated with the record. In particular, this is the largest network where all of the fields besides $ipAddress have the same value. |
   +------------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+


Language
--------

The ``language()`` allows you to get a Language model containing valuable information about the language recognized by the IP address. 

.. code-block:: php
    :caption: example:

    <?php

    $locale = $ip->language()->locale;
    // Returns the locale of the recognied language, e.g., 'en_US'


.. table::
   :width: 100%
   :align: center

   +------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | Attributes | Descriptions                                                                                                                                                        |
   +============+=====================================================================================================================================================================+
   | locale     | The locale corresponding to the language recognized in the country                                                                                                  |
   +------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | all        | An array containing all languages spoken within the specified country.                                                                                              |
   +------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
   | officials  | An array listing the official languages spoken in the specified country.                                                                                            |
   +------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------+
