.. index::
    pair: Tasks; scaffold.php

Tasks
=====

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