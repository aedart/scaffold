<?php

return [

    /* ------------------------------------------------------------
     | Blank Scaffold Configuration
     | ------------------------------------------------------------
     |
     */

    'name'          => 'Blank Scaffold',

    'description'   => 'A blank scaffold project',

    'basePath' => __DIR__ . '/resources/blank/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
        \Aedart\Scaffold\Tasks\AskForTemplateDestination::class,
        \Aedart\Scaffold\Tasks\CreateDirectories::class,
        \Aedart\Scaffold\Tasks\CopyFiles::class,
        \Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class,
    ],

    'folders' => [
        'resources' => [
            'files',
            'snippets'
        ],
    ],

    'files' => [
        // Source files (inside 'basePath')  =>  Destination
    ],

    'templateData' => [

        //
        // Ask the for scaffold name
        //
        'scaffoldName' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

            'question'      => 'Name of the scaffold?',

            'value'         => 'My Scaffold',

            'postProcess'   => function($answer, array $previousAnswers){
                return trim($answer);
            }
        ],

        //
        // Ask the for scaffold description
        //
        'scaffoldDesc' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

            'question'      => 'Description of the scaffold?',

            'value'         => 'This is my great scaffold',

            'postProcess'   => function($answer, array $previousAnswers){
                return trim($answer);
            }
        ],
    ],

    'templates' => [
        'blank' => [
            'source'        => 'snippets/blank.scaffold.php.twig',

            'destination'   => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'Name of scaffold file (automatically appends ".scaffold.php")',

                'value'         => 'new',

                'postProcess'   => function($answer, array $previousAnswers){
                    return trim(strtolower($answer)) . '.scaffold.php';
                }
            ],
        ],
    ],
];