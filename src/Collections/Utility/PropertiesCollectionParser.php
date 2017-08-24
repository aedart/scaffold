<?php namespace Aedart\Scaffold\Collections\Utility;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties;

/**
 * Properties Collection Parser Trait
 *
 * Utility that is able to parse an array into a
 * template properties collection
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Collections\Utility
 */
trait PropertiesCollectionParser
{
    /**
     * Parse the given properties list and return a collection
     *
     * @param array $properties
     *
     * @return TemplateProperties
     */
    public function parsePropertiesCollection(array $properties = [])
    {
        /** @var TemplateProperties $collection */
        $collection = IoC::getInstance()->make(TemplateProperties::class);
        $collection->populate($properties);

        return $collection;
    }
}