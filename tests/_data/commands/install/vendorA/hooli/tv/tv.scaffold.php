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

            'value'         => scaffold_cache()->remember('controllerClass', 10, function(){
                // If caching utility isn't configured correctly, just invoking the
                // scaffold_cache() will fail.
                return 'MyController';
            })
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
        'echo Freebooters stutter from powers like proud shores.',

        [
            'timeout' => 10,
            'script' => 'ls'
        ],

        function(array $config){
            $script = 'cd ' . $config['outputPath'] . ' && ls';

            return new \Aedart\Scaffold\Scripts\CliScript([
                'timeout'   => 10,
                'script'    => $script
            ]);
        }
    ]
];