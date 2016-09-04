.. index::
    triple: Install; Build; Command
    pair: Output; Location

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

Output Location
^^^^^^^^^^^^^^^

By default, bot the :code:`install` and the :code:`build` commands will output the resulting files and directories into
the current working directory, form where you invoked the command. This behaviour can be modified by providing the
commands with an optional :code:`output` directory

.. code-block:: console

    vendor/bin/scaffold install -o path/where/to/install/

.. code-block:: console

    vendor/bin/scaffold build -o path/where/to/install/ other/location/my-scaffold.scaffold.php