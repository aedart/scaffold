Version 1.1.x
=============

v1.1.1 (18-09-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Fixed Fatal error: Can't use function return value in write context (issue existing only in PHP 5.6.x only)

v1.1.0 (18-09-2016)
^^^^^^^^^^^^^^^^^^^

Added
-----
* Added :doc:`../create_scaffolds/helpers/cache` utility (`#3 <https://github.com/aedart/scaffold/issues/3>`_).
* Added release notes documentation
* Added multiple smaller and more maintainable service providers
* Added documentation about why output from :code:`composer` commands (in :code:`scripts`) are logged to :code:`STDERR`

Changed
-------
* Changed :code:`ScaffoldServiceProvider`, has now become an :code:`AggregateServiceProvider` instead. It registers a series of smaller service providers.
