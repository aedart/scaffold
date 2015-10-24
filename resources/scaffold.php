<?php

return [

    'name'      => 'My Template',

    'description'   => 'My awesome template...',

    'basePath' => __DIR__ . '/templates',

    'files' => [
        'composer.json.twig'    => [
            'handler'           => \Aedart\Scaffold\Handlers\FileHandler::class,
            'filename'          => [
                'ask'           => true,
                'description'   => 'Composer file\'s name?',
                'default'       => 'composer.json'
            ],
        ],
        '.gitkeep'              => [
            'handler'   => 'Aedart/Scaffold/Handlers/IgnoreFileHandler::class',
        ],
    ],

    'directories' => 'Aedart/Scaffold/Handlers/DirectoryHandler::class',

    'data'      => [

        'name'      => [
            'ask'           =>  true,
            'description'   =>  'Name of the project',
        ],

        'author_name'  => [
            'ask'           =>  true,
            'description'   =>  'Author\'s name',
            'default'       =>  'Alin Eugen Deac'
        ],

        'author_email'  => [
            'ask'           =>  false,
            'default'       =>  'aedart@gmail.com'
        ],

    ],

];