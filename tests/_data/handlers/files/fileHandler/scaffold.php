<?php

return [

    'basePath' => __DIR__ . '/templates',

    'files' => [
            'composer.json.twig'    => [
                'handler'           => \Aedart\Scaffold\Handlers\FileHandler::class,
                'filename'       => 'composer.json'
            ],
    ],

    'data'      => [

        'name'      => [
            'default'       =>  'aedart/scaffold-file-handler-test'
        ],

        'author_name'  => [
            'default'       =>  'Alin Eugen Deac'
        ],

        'author_email'  => [
            'default'       =>  'aedart@gmail.com'
        ],

    ],

];