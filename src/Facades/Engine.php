<?php namespace Aedart\Scaffold\Facades;

use Aedart\Scaffold\Contracts\Engines\TemplateEngine;
use Illuminate\Support\Facades\Facade;

/**
 * <h1>Engine Facade</h1>
 *
 * @see \Aedart\Scaffold\Engines\Twig
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Facades
 */
class Engine extends Facade{

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return TemplateEngine::class;
    }
}