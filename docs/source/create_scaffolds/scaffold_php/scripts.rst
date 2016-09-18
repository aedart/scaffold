.. index::
    pair: Composer; STDERR
    pair: Composer; STDOUT
    pair: Scripts; scaffold.php
    single: CLI

Scripts
=======

======== =======================================================================
required only if :code:`\Aedart\Scaffold\Tasks\ExecuteScripts::class` is used
======== =======================================================================

CLI scripts (or commands) to be executed

Scripts can be used to perform advanced operations. This could for instance be downloading large files, disk cleanup,
running composer commands... etc.

Multiple scripts are allowed and they can be stated in several ways.

Simple Script
^^^^^^^^^^^^^

.. code-block:: php

    <?php
    return [
        'scripts' => [
            'composer update',
        ]
    ];

Script with Timeout
^^^^^^^^^^^^^^^^^^^

If yor expect your script(s) to require more that :code:`60` seconds, then consider specifying a custom timeout.

.. code-block:: php

    <?php
    return [
        'scripts' => [
            [
                'timeout' => 75, // Default is 60 seconds
                'script' => 'composer install && composer update'
            ],
        ]
    ];

Advanced Script
^^^^^^^^^^^^^^^

If you need to perform very advanced scripts that depends on the scaffold configuration, then you can
achieve this via an anonymous function.

A copy of the scaffold configuration is given as argument.

The function **MUST** return an instance of :code:`\Aedart\Scaffold\Contracts\Scripts\CliScript`.

.. code-block:: php

    <?php
    return [
        'scripts' => [
            function(array $config){
                $script = 'cd ' . $config['outputPath'] . ' && ls';

                return new \Aedart\Scaffold\Scripts\CliScript([
                    'timeout'   => 10,
                    'script'    => $script
                ]);
            },
        ]
    ];

Error when triggering composer commands
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you trigger some kind of :code:`composer` script, then you might see that composer's output is logger to the console
as an error. This is due to that composer chooses to write diagnostic output to :code:`STDERR` instead of :code:`STDOUT`.

You can read more about this in `#1905 <https://github.com/composer/composer/issues/1905>`_ and `#3795 <https://github.com/composer/composer/issues/3795>`_ .