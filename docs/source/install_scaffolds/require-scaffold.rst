.. index::
    pair: Require; Scaffold

Require Scaffold
================

Regardless if you are within a new project or existing, you must first require your desired scaffold(s).
This is done via composer's require command or by specifying it in your composer.json.

.. code-block:: console

    composer require acme/scaffolds

.. code-block:: json

    {
        "name": "acme/my-project",
        "description": "My Proejct",
        "license": "BSD-3-Clause",
        "type": "library",
        "require": {
            "acme/scaffolds": "~1.0"
        },
    }

Global Scaffolds
^^^^^^^^^^^^^^^^

If you have chosen to require your scaffold(s) globally, then they should already be available and there is no need
for you to perform this.