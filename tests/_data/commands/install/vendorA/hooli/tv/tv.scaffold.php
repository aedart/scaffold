<?php

return [

    'name'          => 'Tv',

    'description'   => 'Raid me cannibal, ye mighty son!',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
        \Aedart\Scaffold\Tasks\AskForTemplateDestination::class,
        \Aedart\Scaffold\Tasks\CreateDirectories::class,
        \Aedart\Scaffold\Tasks\CopyFiles::class,
        \Aedart\Scaffold\Tasks\GenerateFilesFromTemplates::class,
    ],

    'folders' => [
        'src' =>    [
            'Controllers',
        ]
    ],

    'files' => [
        'files/README.md'            =>  'README.md',
    ],

    'templateData' => [

        //
        // Ask the user for a controller name
        //
        'controllerName' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

            'question'      => 'Controller Class name name?',

            'value'         => 'MyController'
        ]
    ],

    /* ------------------------------------------------------------
     | Templates
     | ------------------------------------------------------------
     |
     | Think of these as snippets that will be used to generate
     | some kind of file. It could be a composer file, a PHP Class,
     | a .env configuration or perhaps a default README file.
     |
     | Each template has a source template file, e.g. a *.twig
     | file, a destination which you can prompt the user about,
     | before the file is generated.
     |
     | The "destination" property works just like the template
     | data. See "templateData" documentation for further info.
     |
     | Furthermore, the source and destination will assigned as a
     | template variable, and made available inside the template
     | itself!
     |
     | Example
     | 'composer' => [
     |      'source' => 'snippets/composer.json.twig'
     |      'destination' => [
     |          'value'       => 'composer.json'
     |      ],
     |      ...
     | ]
     |
     | Will be available inside the 'snippets/composer.json.twig'
     | template as {{ template.composer.source }},
     | {{ template.composer.destination }} ...etc
     | ------------------------------------------------------------
     |
     | Each destination that you provide (or ask for) is relative
     | to the "output path". This usually means the current working
     | directory of where the scaffold is being installed into!
     */
    'templates' => [
        'controller' => [

            'source'        => 'templates/controller.php.twig',

            'destination'   => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'Controller Filename?',

                'value'         => 'src/Controllers/MyController.php',
            ],
        ]
    ],
];