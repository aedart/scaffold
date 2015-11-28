<?php

return [

    'name'      => 'My Template',

    'description'   => 'My awesome template...',

    'basePath' => __DIR__ . '/templates',

    'directoryHandler' => \Aedart\Scaffold\Handlers\DirectoryHandler::class,

    'templates' => [

        [
            'id'            =>  'composer.json.twig',
            'handler'       =>  \Aedart\Scaffold\Handlers\FileHandler::class, // Should be a "Template-Handler" instead...
            'filename'      =>  [
                'id'            => 'composer_filename',
                'ask'           => true,
                'description'   => 'Composer file\'s name?',
                'default'       => 'composer.json'
            ]
        ],

        [
            'id'            =>  'myFile.txt',
            'handler'       =>  \Aedart\Scaffold\Handlers\CopyFileHandler::class,
            'filename'      =>  [
                'id'            => 'my_file',
                'ask'           => false,
                'default'       => 'someFile.txt'
            ]
        ],

        [
            'id'            =>  '.gitkeep',
            'handler'       =>  'Aedart/Scaffold/Handlers/IgnoreFileHandler::class'
        ],
    ],

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