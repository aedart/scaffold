Version 2.0.x
=============

v2.0.1 (29-10-2017)
^^^^^^^^^^^^^^^^^^^

Fixed
-----
* Incorrect populate of directories collection inside `TwigTemplateHandler`

v2.0.0 (15-10-2017)
^^^^^^^^^^^^^^^^^^^

Added
-----
* Added "build:file" command

Changed
-------
* Changed minimum required PHP version to 7.1.x
* Adapted application to use Laravel 5.5.x. Several interfaces and service providers have changed.
* Adapted application to use Symfony 3.3.x components.
* Deprecated `\Aedart\Scaffold\Testing\Console\Style\ExtendedStyle`, replaced by `\Aedart\Scaffold\Console\Style\ExtendedStyle`.
* Changed `build` command, now supports setting answers to questions via the `input` option.
