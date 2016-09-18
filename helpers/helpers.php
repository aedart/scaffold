<?php

use Aedart\Scaffold\Cache\CacheHelper;

/* ---------------------------------------------------------
 | Cache utilities
 * ---------------------------------------------------------
 | All of these methods are just wrappers for Laravel Cache
 | Repository.
 |
 | @see \Illuminate\Contracts\Cache\Repository
 */

if (! function_exists('scaffold_cache')) {

    /**
     * Returns instance of a cache repository
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    function scaffold_cache()
    {
        return CacheHelper::make();
    }
}

if (! function_exists('scaffold_cache_put')) {

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  \DateTime|float|int  $minutes [optional]
     *
     * @return void
     */
    function scaffold_cache_put($key, $value, $minutes = 5)
    {
        scaffold_cache()->put($key, $value, $minutes);
    }
}

if (! function_exists('scaffold_cache_forever')) {

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return void
     */
    function scaffold_cache_forever($key, $value)
    {
        scaffold_cache()->forever($key, $value);
    }
}

if (! function_exists('scaffold_cache_has')) {

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     *
     * @return bool
     */
    function scaffold_cache_has($key)
    {
        return scaffold_cache()->has($key);
    }
}

if (! function_exists('scaffold_cache_get')) {

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default [optional]
     *
     * @return mixed
     */
    function scaffold_cache_get($key, $default = null)
    {
        return scaffold_cache()->get($key, $default);
    }
}

if (! function_exists('scaffold_cache_pull')) {

    /**
     * Retrieve an item from the cache and delete it.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    function scaffold_cache_pull($key, $default = null)
    {
        return scaffold_cache()->pull($key, $default);
    }
}

if (! function_exists('scaffold_cache_forget')) {

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     *
     * @return bool
     */
    function scaffold_cache_forget($key)
    {
        return scaffold_cache()->forget($key);
    }
}

if (! function_exists('scaffold_cache_flush')) {

    /**
     * Flushes the registered cache directory
     *
     * @return void
     */
    function scaffold_cache_flush()
    {
        $cache = scaffold_cache();

        /** @var \Illuminate\Contracts\Cache\Store $store */
        $store = $cache->getStore();
        $store->flush();
    }
}