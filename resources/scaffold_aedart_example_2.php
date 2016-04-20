<?php

return [

    /* ------------------------------------------------------------
     | Scaffold Example configuration
     | ------------------------------------------------------------
     |
     | Each scaffold should follow this naming convention;
     | "scaffold_" [vendor] "_" [your namespace] "_" [name of scaffold] ".php"
     | and should be placed in the root of your package.
     |
     | Example:
     | scaffold_aedart_templates_composer.php
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
    'basePath' => __DIR__ . '/templates',

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
     | NOTE: The files are read from inside the 'basePath'.
     */
    'files' => [
        // Source files (inside 'basePath')  =>  Destination directory
        'gitFiles/.gitkeep'         =>  'config',
        'gitFiles/.ignore'          =>  '',
        'gfx/logo.png'              =>  'tmp',
        'docs/LICENSE'              =>  '',
        'docs/README.md'            =>  '',
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
     | file, a destination where it will be created and a
     | filename, which you can prompt the user about, before
     | the file is generated.
     |
     | Furthermore, the source, destination and filename will
     | assigned as a template variable, and made available
     | inside the template itself!
     |
     | Example
     | 'composer' => [
     |      'source' => 'snippets/composer.json.twig'
     |      'filename' => 'composer.json'
     |      ...
     | ]
     | Will be available inside the 'snippets/composer.json.twig'
     | template as {{ composer.source }}, {{ composer.filename }}
     | ...etc
     */
    'templates' => [
        'composer' => [
            // Template to use, from within the 'basePath'
            'source'        => 'snippets/composer.json.twig',

            // Where the file must be generated into (current working directory is the root)
            'destination'   => '',

            // The filename, which is given to the generated file.
            // if 'ask' is set to true, then the user is prompted
            // and asked to state a filename. If so, then the
            // description will be displayed.
            'filename'      =>  [
                'ask'           => false,
                'description'   => 'Composer file\'s name?',
                'default'       => 'composer.json'
            ]
        ],

        'wiki' => [
            'source'        => 'snippets/WIKI.md.twig',
            'destination'   => 'docs',
            'filename'      =>  [
                'ask'           => true,
                'description'   => 'Name of the Wiki article',
                'default'       => 'WIKI.md'
            ]
        ],
    ],

    /* ------------------------------------------------------------
     | Template Data
     | ------------------------------------------------------------
     |
     | All of these properties will be assigned to ALL templates
     | for this scaffold. Just like the filename, for each template,
     | you can set 'ask' to true and the user will be prompted
     | for a value
     */
    'templateData' => [
        'name' => [
            'ask'           => true,
            'description'   => 'Name of the project',
        ],

        'authorName' => [
            'ask'           => false,
            'description'   => 'Author\'s name',
            'default'       => 'Alin Eugen Deac'
        ],

        'authorEmail' => [
            'ask'           => false,
            'description'   => 'Author\'s email',
            'default'       => 'aedart@gmail.com'
        ],
    ],

    /* ------------------------------------------------------------
     | Scaffold handlers
     | ------------------------------------------------------------
     |
     | Here you can change the default handlers for this scaffold.
     */
    'handlers' => [

        'directories'     =>    \Aedart\Scaffold\Handlers\DirectoryHandler::class,

        'files'           =>    \Aedart\Scaffold\Handlers\CopyFileHandler::class,

        'templates'       =>    \Aedart\Scaffold\Handlers\TemplateHandler::class,
    ],
];