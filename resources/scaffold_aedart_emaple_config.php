<?php

return [

    'name'      => 'My Template',

    'description'   => 'My awesome template...',

    'basePath' => __DIR__ . '/templates',

    'directoryHandler' => \Aedart\Scaffold\Handlers\DirectoryHandler::class,

    // TODO:        Perhaps the structure should be changed, and a section purely for files
    // TODO:        should be added, stating what files should be copied, which not, keeping
    // TODO:        templates completely clean!

    'templates' => [

        [
            'id'            =>  'composer.json.twig',
            'handler'       =>  \Aedart\Scaffold\Handlers\TemplateHandler::class,
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
            'handler'       =>  \Aedart\Scaffold\Handlers\IgnoreFileHandler::class,
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