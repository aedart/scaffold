<?php

return [

    'name'          => 'Folders only',

    'description'   => 'Creates only a few folders',

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