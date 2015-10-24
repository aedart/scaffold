<?php

use Faker\Factory;

$faker = Factory::create();

return [

    'basePath' => __DIR__ . '/templates',

    'files' => [
            'composer.json.twig'    => [
                'handler'           => \Aedart\Scaffold\Handlers\FileHandler::class,
                'filename'          => [
                    'default' => 'composer.json'
                ],
            ],
    ],

    'data'      => [

        'name'      => [
            'default'       =>  'aedart/scaffold-file-handler-test'
        ],

        'author_name'  => [
            'default'       =>  $faker->name
        ],

        'author_email'  => [
            'default'       =>  $faker->email
        ],

    ],

];