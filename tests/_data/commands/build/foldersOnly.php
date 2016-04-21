<?php

return [

    'name'          => 'Folders only',

    'description'   => 'To be used for testing only - test if the build command can create directories',

    'basePath' => __DIR__ ,

    'folders' => [
            'app',
            'config',
            'src' =>    [
                'Contracts' => [
                    'Models',
                    'Events',
                    'Listeners' => [
                        'Jobs',
                        'Logs',
                        'DbEntries'
                    ]
                ],
                'Controllers',
                'Events',
                'Models',
            ],
            'tmp'
    ]
];