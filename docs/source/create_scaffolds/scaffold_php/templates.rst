.. index::
    pair: Templates; scaffold.php

Templates
=========

======== =======================================================================
required only if the following tasks are used
======== =======================================================================
a        :code:`\Aedart\Scaffold\Tasks\AskForTemplateData::class`,
b        :code:`\Aedart\Scaffold\Tasks\AskForTemplateDestination::class`,
c        :code:`\Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class`
======== =======================================================================

The snippets (or templates) from which one or several files must be generated from.

Each defined template assumes that it's source `Twig <http://twig.sensiolabs.org/>`_ template is located inside
the :code:`basePath`.

See :doc:`base-path`

.. code-block:: php

    <?php
    return [
        'templates' => [
            'myTemplate' => [
                'source'        => 'snippets/env.twig',

                'destination'   => [

                    'value'       => '.env',
                ],
            ],
        ],
    ];

Ask for template destination
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The :code:`destination` property works in the same way as with the :code:`templateData`, in that you can use it to ask
the end user for a file destination. All types are supported.

See :doc:`template-data`.

.. code-block:: php

    <?php
    return [
        'templates' => [
            'myTemplate' => [
                'source'        => 'snippets/markdown.twig',

                // Destination of a file - uses a "question" property
                'destination'   => [

                    'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                    'question'      => 'Name / location of file? (.md automatically added)',

                    'value'         => 'WIKI',

                    // Use post processing to add file extension
                    'postProcess'   => function($answer, array $previousAnswers){
                        return $answer . '.md';
                    }
                ],
            ],
        ],
    ];