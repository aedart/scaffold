<?php

return [

    'name'          => 'Files only',

    'description'   => 'To be used for testing only - test if the build command can copy files',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\CopyFiles::class
    ],

    'files' => [
        // Source files (inside 'basePath')  =>  Destination
        '.gitkeep'                  =>  '.gitkeep',
        'logs/default.log'          =>  'log.log',
        'docs/LICENSE.txt'          =>  'LICENSE',
        'docs/README.md'            =>  'README.md',
    ],
];