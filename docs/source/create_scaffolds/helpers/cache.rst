.. index::
    single: Cache

Cache
=====

Available since version :code:`1.1.0`

Confronting an end-user with too many questions can quickly become a hassle for them. Therefore, if you find yourself in
a situation where you need a lot of user input, consider caching some of the input.

Caching is made available in a scaffold, by making use of the :code:`scaffold_cache_*` methods, which are defined inside
the :code:`helpers.php` file.

Behind the Scene
^^^^^^^^^^^^^^^^

The caching system that is offered by the Scaffold Application, is basically just a wrapper around Laravel's
`Cache <https://laravel.com/docs/5.3/cache>`_ . It is configured to use the :code:`\Illuminate\Cache\FileStore`, storing
values withing the user provided cache directory.

The cache directory is set via a :code:`--cache` option on each available command that makes use of caching. It defaults
:code:`.scaffold/cache/` if the option is not provided.

Example
^^^^^^^

In this example, once a user has answered the question first time, the scaffold will use a cached answer as the default
value. Next time the scaffold is built, the default value will become the cached value.

.. code-block:: php

    <?php
    return [
        'templateData' => [

            'name' => [

                'type'          => \Aedart\Scaffold\Contracts\Templates\Data\Type::QUESTION,

                'question'      => 'What is your name?',

                'value'         => scaffold_cache_get('name', 'John Doe'),

                'postProcess'   => function($answer, array $previousAnswers){
                    // Update the cached value, in case user provides a "new" answer
                    scaffold_cache_put('name', $answer, 60);

                    return $answer;
                }
            ],
        ],
    ];

Available Methods
^^^^^^^^^^^^^^^^^

Please consider `Laravel's documentation <https://laravel.com/docs/5.3/cache>`_ for further information and examples
of using the cache.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache()`

Returns an instance of :code:`\Illuminate\Contracts\Cache\Repository`.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_put($key, $value, $minutes = 5)`

Store an item in the cache.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_remember($key, $minutes, Closure $callback)`

Get an item from the cache, or store the default value.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_forever($key, $value)`

Store an item in the cache indefinitely.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_has($key)`

Determine if an item exists in the cache.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_get($key, $default = null)`

Retrieve an item from the cache by key.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_pull($key, $default = null)`

Retrieve an item from the cache and delete it.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_forget($key)`

Remove an item from the cache.

---------------------------------------------------------------------------------------------------

:code:`scaffold_cache_flush()`

Flushes the registered cache directory.

---------------------------------------------------------------------------------------------------