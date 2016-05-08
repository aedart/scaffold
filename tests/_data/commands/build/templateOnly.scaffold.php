<?php

return [

    'name'          => 'Template only',

    'description'   => 'To be used for testing only - test if the build command can ask for template destination',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateDestination::class
    ],

    'templates' => [
        'composer' => [
            'source'        => 'snippets/myClass.twig',

            'destination'   => [

                'value'       => 'models/User.php'
            ],
        ]
    ],
];