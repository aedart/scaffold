.. index::
    pair: TemplateData; scaffold.php

TemplateData
============

======== =======================================================================
required only if the following tasks are used
======== =======================================================================
a        :code:`\Aedart\Scaffold\Tasks\AskForTemplateData::class`,
b        :code:`\Aedart\Scaffold\Tasks\AskForTemplateDestination::class`,
c        :code:`\Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class`
======== =======================================================================

Data to be used inside `Twig <http://twig.sensiolabs.org/>`_ templates. Depending upon the template-data's
:code:`type`, the end-user might be asked in the console for an answer.

You can specify as many template data entries as you wish.

.. code-block:: php

    <?php
    return [

        'templateData' => [

            'myProperty' => [
                'value'       => 'My property value'
            ],
        ],

    ];

Syntax
^^^^^^

.. code-block:: php

    <?php
    return [
        'templateData' => [
            'name-of-property' => [
                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION
                'question'      => 'The question to ask the user'
                'value'         => (default value, in case user just hits enter),
                'validation'    => function($answer){return $answer;},
                'postProcess'   => function($answer, array $previousAnswers){return $answer;},
            ],
        ]
    ];

The :code:`postProcess` callback method is automatically provided with eventual previous values. This gives you the
possibility creating computed values that combine previous given answers from the end-user. The :code:`postProcess`
is available for all supported types.

In the following sections, an example is shown on each supported type.

Question
^^^^^^^^

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myQuestion' => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'What is your vendor name? (used as namespace prefix)',

                'value'         => 'Acme',

                'validation'    => function($answer){
                    if(strlen($answer) >= 3){
                        return $answer;
                    }

                    throw new \RuntimeException('Vendor name should be at least 3 chars long');
                },

                'postProcess'   => function($answer, array $previousAnswers){
                    return ucfirst($answer);
                }
            ],
        ],
    ];

Choice
^^^^^^

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myChoice' => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::CHOICE,

                'question'      => 'In what sub-namespace should your class be located?',

                'choices'       => [
                    'Controllers',
                    'Events',
                    'Models'
                ],

                'value'         => 'Controllers',
            ],
        ],
    ];

Confirm
^^^^^^^

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myConfirm' => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::CONFIRM,

                'question'      => 'Add class PHPDoc?',

                'value'         => true,
            ],
        ],
    ];

Hidden
^^^^^^

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myPassword' => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::HIDDEN,

                'question'      => 'What password should be stored inside the .env?',

                'validation'    => function($answer){
                    if(strlen($answer) >= 6){
                        return $answer;
                    }

                    throw new \RuntimeException('The password should be at least 6 characters long');
                },
            ],
        ],
    ];

Value
^^^^^

When using a simple value, the end-user is not prompted with any questions. Also, the type does not have to be declared
explicitly.

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myValue' => [
                'value'       => 'My Value'
            ],
        ],
    ];

It is also possible to perform post-processing of simple values. It can be used to create a computed value which
consists of the given value and perhaps a previously given value.

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'myValue' => [
                'value'       => 'My Value',
                'postProcess'   => function($answer, array $previousAnswers){
                    return $answer . $previousAnswers['myOtherValue'];
                }
            ],
        ],
    ];