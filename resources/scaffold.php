<?php

return [

    'name'      => 'My Template',

    'description'   => 'My awesome template...',

    'basePath' => __DIR__ . '/templates',

    'files' => [
        'composer.json.twig'    => [
            'handler'           => \Aedart\Scaffold\Handlers\FileHandler::class,
            'filename'          => [
                'id'            => 'composer_filename',
                'ask'           => true,
                'description'   => 'Composer file\'s name?',
                'default'       => 'composer.json'
            ]
        ],
        '.gitkeep'              => [
            'handler'   => 'Aedart/Scaffold/Handlers/IgnoreFileHandler::class',
        ],
    ],

    'directories' => 'Aedart/Scaffold/Handlers/DirectoryHandler::class',

    'data'      => [

        [
            'id'            => 'name',
            'ask'           => true,
            'description'   => 'Name of the project',
        ],

        [
            'id'            => 'author_name',
            'ask'           => false,
            'description'   => 'Author\'s name',
            'default'       => 'Alin Eugen Deac'
        ],

        [
            'id'            => 'author_email',
            'ask'           => false,
            'description'   => 'Author\'s email',
            'default'       => 'aedart@gmail.com'
        ],

    ],

];