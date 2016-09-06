.. index::
    single: Testing
    pair: UnitTest; PHPUnit
    pair: Symfony; Command Tester

Testing
=======

Depending on your scaffold complexity, the fastest way of testing it is manually, by building it locally.

.. code-block:: console

    vendor/bin/scaffold install -a -o temp/

The above command will first index your scaffold and thereafter install it. All of it's output will go into the
:code:`temp` directory.

Automatic Testing
^^^^^^^^^^^^^^^^^

If your scaffold(s) have become very complex, require lots of user input or perhaps depend on many resources, then
perhaps it is better to write some kind of automatic test for, e.g. via `PHPUnit <https://phpunit.de/>`_ .

The current version of the scaffold application does not offer any testing utilities. However, you can review the
:code:`tests\integration\InstallCommandTest` as inspiration on how to write automatic tests for your
scaffold.

Behind the scenes, `Symfony's Command Tester <http://symfony.com/doc/current/console.html#testing-commands>`_ is being
used.

In future versions, this package might include better testing utilities.