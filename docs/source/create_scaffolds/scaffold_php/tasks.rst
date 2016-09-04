.. index::
    pair: Tasks; scaffold.php

Tasks
=====

======== =======
required yes
======== =======

An ordered list of all the tasks that must be executed. These tasks are responsible for building your project or
resource.

By default, several tasks are included in a blank scaffold. You can change these are you see fit, but keep in mind
that the order does matter.

.. code-block:: php

    <?php
    return [

        'tasks' => [
            \Aedart\Scaffold\Tasks\AskForTemplateData::class,
            \Aedart\Scaffold\Tasks\AskForTemplateDestination::class,
            \Aedart\Scaffold\Tasks\CreateDirectories::class,
            \Aedart\Scaffold\Tasks\CopyFiles::class,
            \Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class,
        ],

    ];

.. note::

    You can remove all of the default tasks and replace them with your own.

    When creating custom tasks, just ensure that you implement the
    :code:`\Aedart\Scaffold\Contracts\Tasks\ConsoleTask` interface.

    You can also just extend the :code:`\Aedart\Scaffold\Tasks\BaseTask` abstract task,
    which does some of the ground work for you.

    Please review the documentation inside the source code for further information.