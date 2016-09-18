<?php

return [

    'name'          => 'Utility B',

    // Don't know why someone would cache a scaffolds description, yet
    // we need to ensure that the index can handle this, since the entire array
    // it processed!
    'description'   => scaffold_cache()->remember('specialDescription', 1, function(){
        return 'The bilge rat commands with grace, drink the pacific ocean before it hobbles.';
    }),
];