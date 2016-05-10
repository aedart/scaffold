<?php namespace Aedart\Scaffold\Collections\Utility;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\Templates;

/**
 * Template Collection Parser Trait
 *
 * Utility that is able to parse an array into a
 * template collection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections\Utility
 */
trait TemplateCollectionParser
{

    /**
     * Parse the given properties list and return a collection
     *
     * @param array $templates
     *
     * @return Templates
     */
    public function parseTemplatesCollection(array $templates = [])
    {
        $ioc = IoC::getInstance();

        return $ioc->make(Templates::class, $templates);
    }
}