Version 1.1.x
=============

v1.1.5 (01-10-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Re-fixed indexing order. First fix was correct, yet due to incorrect manual test, the "issue" didn't seemed fixed.

v1.1.4 (01-10-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Re-fixed order of default indexing directories. First fix caused undesired indexing results.

v1.1.3 (01-10-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Fixed order of default index directories.

v1.1.2 (22-09-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Fixed `#4 <https://github.com/aedart/scaffold/issues/4>`_ Parse error: syntax error, unexpected '->' (T_OBJECT_OPERATOR) (issue in PHP 5.6.x only)

v1.1.1 (18-09-2016)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Fixed Fatal error: Can't use function return value in write context (issue in PHP 5.6.x only)

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
