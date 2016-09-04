.. index::
    pair: Files; scaffold.php

Files
=====

======== =======================================================================
required only if :code:`\Aedart\Scaffold\Tasks\CopyFiles::class` is used
======== =======================================================================

Copies files from :code:`basePath` and into the output directory.

See :doc:`base-path`

.. code-block:: php

    <?php
    return [

        'files' => [
            // Source files (inside 'basePath')  =>  Destination
            '.gitkeep'                  =>  '.gitkeep',
            'logs/default.log'          =>  'tmp/log.log',
            'docs/LICENSE.txt'          =>  'LICENSE',
        ],

    ];

.. warning::

    You should avoid large files. If your template requires such, then you should perhaps consider a custom
    task that can download them from an external source!