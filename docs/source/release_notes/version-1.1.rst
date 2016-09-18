Version 1.1.x
=============

v1.1.0 (xx-09-2016)
^^^^^^^^^^^^^^^^^^^

Added
-----
* Added release notes documentation
* Added multiple smaller and more maintainable service providers
* Added documentation about why output from :code:`composer` commands (in :code:`scripts`) are logged to :code:`STDERR`

Changed
-------
* Changed :code:`ScaffoldServiceProvider`, has now become an :code:`AggregateServiceProvider` instead. It registers a series of smaller service providers.

Fixed
-----
