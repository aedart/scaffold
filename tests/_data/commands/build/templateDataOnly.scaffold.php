<?php

return [

    'name'          => 'Template Data only',

    'description'   => 'To be used for testing only - test if the build command can ask for template data',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
    ],

    'templateData' => [
//        'packageName' => [
//            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,
//            'question'      => 'What this package\'s name (composer.json "name" property)?',
//            'value'         => 'aedart/scaffold-example',
//            'validation'    => function($answer){
//                if(strpos($answer, '/') !== false){
//                    return $answer;
//                }
//
//                throw new \RuntimeException('Package name must contain vendor and project name, separated by "/"');
//            },
//            'postProcess'   => function($answer){
//                return trim(strtolower($answer));
//            }
//        ],

        'packageName' => [
            'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::VALUE,
            'value'         => ' AEDART/SCAFFOLD-EXAMPLE ',
            'postProcess'   => function($answer){
                return trim(strtolower($answer));
            }
        ],
    ],
];