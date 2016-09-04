.. index::
    pair: Folders; scaffold.php

Folders
=======

======== =======================================================================
required only if :code:`\Aedart\Scaffold\Tasks\CreateDirectories::class` is used
======== =======================================================================

List of directories (or folders) that will be created.

.. code-block:: php

    <?php
    return [

        'folders' => [
                'app',
                'config',
                'src' =>    [
                    'Contracts',
                    'Controllers',
                    'Events',
                    'Models',
                ],
                'tmp'
        ],

    ];