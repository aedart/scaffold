<?php

return [

    /* ------------------------------------------------------------
     | Scaffold Example configuration
     | ------------------------------------------------------------
     |
     */

    /* ------------------------------------------------------------
     | Name of this Scaffold
     | ------------------------------------------------------------
     |
     | This name is displayed in the list of available templates,
     | when the CLI command is invoked.
     */
    'name'          => 'My Template',

    /* ------------------------------------------------------------
     | Description of this Scaffold
     | ------------------------------------------------------------
     |
     | This description is displayed in the list of available
     | templates, when the CLI command is invoked.
     */
    'description'   => 'This is my awesome template...',

    /* ------------------------------------------------------------
     | Location of scaffold's files and templates
     | ------------------------------------------------------------
     |
     | This path tells the scaffold where it can find it's
     | resource, from which it can build and install one or several
     | folders, files and or templates.
     */
    'basePath' => __DIR__ . '/resources/',

    /* ------------------------------------------------------------
     | Tasks
     | ------------------------------------------------------------
     |
     | Ordered list of all the tasks that must be executed. These
     | tasks are responsible for building your project or resource.
     */
    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
        \Aedart\Scaffold\Tasks\AskForTemplateDestination::class,
        \Aedart\Scaffold\Tasks\CreateDirectories::class,
        \Aedart\Scaffold\Tasks\CopyFiles::class,
    ],

    /* ------------------------------------------------------------
     | Folders
     | ------------------------------------------------------------
     |
     | Each folder (or directory) stated in this array will be
     | created, if it does not already exist.
     |
     | NOTE: Directories are created from the current working
     | directory as the top-most level.
     |
     | Example:
     | Lets assume that you wish to create a set of predefined
     | directories in /home/projects/MyProject/.
     | When the scaffold CLI is executed, then all of the
     | below stated directories will be created inside the
     | mentioned root directory;
     |
     | /home/projects/MyProject/app/
     | /home/projects/MyProject/config/
     | /home/projects/MyProject/src/
     | /home/projects/MyProject/src/Contracts/
     | /home/projects/MyProject/src/Contracts/Controllers
     | ...etc
     */
    'folders' => [
            'app',
            'config',
            'src' =>    [
                'Contracts',
                'Controllers',
                'Events',
                'Models',
            ],
            'tmp'
    ],

    /* ------------------------------------------------------------
     | Files
     | ------------------------------------------------------------
     |
     | Each file will be copied into your project directory,
     | if it does NOT already exist.
     |
     | WARNING: You should avoid large files. If your template
     | requires such, then you should perhaps consider a custom
     | command that can download them from an external source!
     |
     | NOTE: The files are read from inside the 'basePath'.
     */
    'files' => [
        // Source files (inside 'basePath')  =>  Destination
        '.gitkeep'                  =>  '.gitkeep',
        'logs/default.log'          =>  'tmp/log.log',
        'docs/LICENSE.txt'          =>  'LICENSE',
        'docs/README.md'            =>  'README.md',
    ],

    /* ------------------------------------------------------------
     | Template Data
     | ------------------------------------------------------------
     |
     | In this section, all the "variables" that are needed by
     | templates are defined. (Internally, these are called
     | "properties").
     |
     | Each defined variable or "property" has a type, that
     | determines how the system should deal with it. Depending on
     | that type, a value is either just passed on to the templates
     | or the end-user is prompted (asked) for a value, in the
     | console.
     |
     | By default, the system supports the types that are defined
     | within the Type interface.
     | @see \Aedart\Scaffold\Contracts\Templates\Data\Type
     | ------------------------------------------------------------
     |
     | Syntax
     |
     | As a minimum, each variable should be defined in the
     | following way;
     |
     | 'name-of-property' => [
     |      'value' => (the value of the property)
     | ],
     |
     | If you need the end-user to provide you with a value, then
     | you must state a type. It will determine how the user is
     | going to be asked for that value. Furthermore, additional
     | settings might be required, such as a 'question' property,
     | that is displayed to the user.
     |
     | 'name-of-property' => [
     |      'type'      => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION
     |      'question'  => 'The question to ask the user'
     |      'value'     => (default value, in case user just hits enter)
     | ],
     |
     | See the resources/examples/example.scaffold.php for what
     | settings are supported by the default types.
     | ------------------------------------------------------------
     |
     | Validation and post processing
     |
     | Validation and post-processing of the values is also
     | supported. This is done so, via callback methods.
     |
     | 'name-of-property' => [
     |      'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION
     |      'question'      => 'The question to ask the user'
     |      'value'         => (default value, in case user just hits enter),
     |      'validation'    => function($answer){return $answer;},
     |      'postProcess'   => function($answer){return $answer;},
     | ],
     | ------------------------------------------------------------
     |
     | Behind the scenes, the Symfony Style utility is used as a
     | helper to prompt the user for a value.
     | @see http://symfony.com/blog/new-in-symfony-2-8-console-style-guide
     */
    'templateData' => [

        //
        // Ask the user for a package name
        //
        'packageName' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

            'question'      => 'What this package\'s name (composer.json "name" property)?',

            'value'         => 'aedart/scaffold-example',

            'validation'    => function($answer){
                if(strpos($answer, '/') !== false){
                    return $answer;
                }

                throw new \RuntimeException('Package name must contain vendor and project name, separated by "/"');
            },

            'postProcess'   => function($answer){
                return trim(strtolower($answer));
            }
        ],

        //
        // Ask the user for a package type; allow only predefined answers.
        //
        'packageType' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::CHOICE,

            'question'      => 'What type of this package is this (composer.json "type" property)?',

            'choices'       => [
                'library',
                'project',
                'metapackage',
                'composer-plugin'
            ],

            'value'         => 'library',
        ],

        //
        // Ask the user to confirm something; (yes / no)
        //
        'requirePredefinedDev' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::CONFIRM,

            'question'      => 'Require predefined development packages (composer.json "require-dev" property)?',

            'value'         => false,
        ],

        //
        // Ask the user for some sensitive information (the input is not displayed)
        // and then ask the user to repeat his/hers answer.
        //
        'envPassword' => [

            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::HIDDEN,

            'question'      => 'What password should be stored inside the .env?',

            'validation'    => function($answer){
                if(strlen($answer) >= 6){
                    return $answer;
                }

                throw new \RuntimeException('The password should be at least 6 characters long');
            },
        ],

        //
        // Define a default property; don't ask the user anything.
        // (Type defaults to \Aedart\Scaffold\Contracts\Templates\Data\Type::VALUE)
        //
        'authorName' => [
            'value'       => 'Alin Eugen Deac'
        ],

        //
        // Define a default property; don't ask the user anything.
        // (Type defaults to \Aedart\Scaffold\Contracts\Templates\Data\Type::VALUE)
        //
        'authorEmail' => [
            'value'       => 'aedart@gmail.com'
        ],
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
        'composer' => [
            // Template to use, from within the 'basePath'
            'source'        => 'snippets/composer.json.twig',

            // Destination of a composer file - uses a "default"
            // property (Type defaults to \Aedart\Scaffold\Contracts\Templates\Data\Type::VALUE)
            'destination'   => [

                'value'       => 'composer.json'
            ],
        ],

        'wiki' => [
            'source'        => 'snippets/WIKI.md.twig',

            // Destination of a markdown file - uses a type "question" property
            'destination'   => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'Name and location of WIKI markdown file?',

                'value'         => 'docs/WIKI.md',
            ],
        ],
    ],

    /* ------------------------------------------------------------
     | Scaffold handlers
     | ------------------------------------------------------------
     |
     | These handlers are used by some of the default tasks. They
     | provide an additional level of flexibility, in which you
     | can use the default tasks, yet change the way that each of
     | them handles certain operations.
     |
     | If you do not plan to change the default tasks' behaviour,
     | then you can leave out this part of the configuration.
     */
//    'handlers' => [
//
//        'directory'     =>    \Aedart\Scaffold\Handlers\DirectoriesHandler::class,
//
//        'file'          =>    \Aedart\Scaffold\Handlers\FilesHandler::class,
//
//        'property'      =>    \Aedart\Scaffold\Handlers\PropertyHandler::class,
//
//        'template'      =>    \Aedart\Scaffold\Handlers\TwigTemplateHandler::class,
//    ],
];