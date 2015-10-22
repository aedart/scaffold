<?php

return [

    'basePath' => __DIR__ . '/templates',

    'files' => [
            'composer.json.twig'    => \Aedart\Scaffold\Handlers\FileHandler::class,
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