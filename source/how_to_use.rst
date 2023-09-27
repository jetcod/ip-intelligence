.. _how_to_use

How to Use?
***********


The `jetcod/ip-intelligence` package provides a comprehensive solution for performing IP address lookups and obtaining valuable geographical and language-related information. This documentation outlines the available methods and their functionality.


IP Lookup
=========


ip($address)
------------

The ``ip()`` method is employed to instantiate the ``GeoIpLookup`` object with a valid IP address. Subsequently, you can utilize additional methods to retrieve valuable information associated with the IP address.

.. code-block:: php

    <?php

    $address = '206.47.249.128';
    $ip = $lookup->ip($address);


country()
---------

The ``country()`` method allows you to retrieve various country-related details associated with the IP address.

.. code-block:: php

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


city()
------

The ``city()`` method allows you to retrieve various city-related details associated with the IP address.

.. code-block:: php

    <?php

    $countryName = $ip->city()->name;
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

Error Handling
==============

The code example also includes error handling for potential exceptions:

- ``InvalidIpAddressException``: This exception is thrown when the provided IP address is invalid.

- ``AddressNotFoundException``: This exception is thrown when the IP address is not found in the database.