<?php

return [

    'name'          => 'Tv',

    'description'   => 'Raid me cannibal, ye mighty son!',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
        \Aedart\Scaffold\Tasks\AskForTemplateDestination::class,
        \Aedart\Scaffold\Tasks\CreateDirectories::class,
        \Aedart\Scaffold\Tasks\CopyFiles::class,
        \Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class,
    ],

    'folders' => [
        'src' =>    [
            'Controllers',
        ]
    ],

    'files' => [
        'files/README.md'            =>  'README.md',
    ],

    'templateData' => [

        //
        // Ask the user for a controller name
        //
        'controllerName' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

            'question'      => 'Controller Class name name?',

            'value'         => 'MyController'
        ]
    ],

    'templates' => [
        'controller' => [

            'source'        => 'templates/controller.php.twig',

            'destination'   => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'Controller Filename?',

                'value'         => 'src/Controllers/MyController.php',
            ],
        ]
    ],

    'scripts' => [
        [
            'timeout' => 65,
            'script' => 'ls'
        ],
        'echo Freebooters stutter from powers like proud shores.'
    ]
];