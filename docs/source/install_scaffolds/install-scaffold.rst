.. index::
    triple: Build-File; File; Command
    triple: Install; Build; Command
    pair: Output; Location
    Single: Build
    Single: Install
    Single: Build-File

Install Scaffold
================

Install Command
^^^^^^^^^^^^^^^

The easiest way to install a your desired scaffold, is by invoking the :code:`install` command

.. code-block:: console

    vendor/bin/scaffold install

This command will automatically create an index of all the found scaffolds and start displaying a list of vendors,
packages and scaffolds you can chose to install. It should output something similar to this;

.. code-block:: console

    Building index
    ==============

    ! [NOTE] Building new index (.scaffold/index.json)

    Searching in /home/Code/my-project/vendor

    ! [NOTE]  Indexing /home/Code/my-project/vendor/acme/scaffolds/my-scaffold.scaffold.php

    Searching in /home/.composer/vendor

    ! [NOTE] No scaffolds found in /home/.composer/vendor

    [OK] Indexing completed


    Install...
    ==========

    Please select a vendor:
    [0] acme
    >

.. tip::

    The install command supports several options. See command help for additional information.

    .. code-block:: console

        vendor/bin/scaffold install -h

Build Command
^^^^^^^^^^^^^

If you wish to skip the install procedure (listing of vendors, packages ... etc), then you can use the :code:`build`
command to specify your desired scaffold directly.

.. code-block:: console

    vendor/bin/scaffold build other/location/my-scaffold.scaffold.php

Build From File Command
^^^^^^^^^^^^^^^^^^^^^^^

Sometimes it can be useful to automate the building process. For instance, if you want to build several scaffolds
at once, then you can use the :code:`build:file` command.

.. code-block:: console

    vendor/bin/scaffold build:file location/scaffoldsToBuild.php

The command accepts a single argument, :code:`file`, which is a location to a php file that contains a list of
scaffolds to be built.

In other words, this command allows you to build multiple scaffolds in one and the same process.

File Format
---------------

The following illustrates the format that the :code:`build:file` command accepts.

.. code-block:: php

    <?php

    return [
        [
            // Location to scaffold
            'location'  => __DIR__ . '/MyModel.scaffold.php',

            // Input (answers to questions) for that scaffold
            'input'     => [
                'AEDART/a'
            ]
        ],
        [
            'location'  => __DIR__ . '/MyController.scaffold.php',
            'input'     => [
                'Acme/b'
            ]
        ],
        [
            'location'  => __DIR__ . '/MyView.scaffold.php',
            'input'     => [
                'Punk/c'
            ]
        ],
    ];

Output Location
^^^^^^^^^^^^^^^

By default, bot the :code:`install` and the :code:`build` commands will output the resulting files and directories into
the current working directory, form where you invoked the command. This behaviour can be modified by providing the
commands with an optional :code:`output` directory

.. code-block:: console

    vendor/bin/scaffold install -o path/where/to/install/

.. code-block:: console

    vendor/bin/scaffold build -o path/where/to/install/ other/location/my-scaffold.scaffold.php