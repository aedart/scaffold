<?php

return [

    'basePath' => __DIR__ . '/templates',

    'files' => [
            'composer.json.twig'    => \Aedart\Scaffold\Handlers\FileHandler::class,
    ],

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