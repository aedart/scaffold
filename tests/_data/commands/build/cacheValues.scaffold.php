<?php

return [

    'name'          => 'Cache Values',

    'description'   => 'To be used for testing only - test if caching works',

    'basePath' => __DIR__ . '/resources/',

    'tasks' => [
        \Aedart\Scaffold\Tasks\AskForTemplateData::class,
    ],

    'templateData' => [
        'sentence' => [
            'value'         => scaffold_cache()->remember('mySentence', 10, function(){
                return 'Damn yer grog, feed the lass.';
            }),
            'postProcess'   => function($answer){
                return scaffold_cache_get('mySentence');
            }
        ],
    ],
];