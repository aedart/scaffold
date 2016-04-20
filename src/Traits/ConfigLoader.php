<?php namespace Aedart\Scaffold\Traits;

use Aedart\Config\Loader\Factories\DefaultParserFactory;
use Aedart\Config\Loader\Loaders\ConfigLoader as Loader;
use Aedart\Config\Loader\Traits\ConfigLoaderTrait;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Configuration Loader
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait ConfigLoader
{
    use ConfigLoaderTrait;

    public function getDefaultConfigLoader()
    {
        $loader = new Loader();

        $loader->setConfig(new Repository());
        $loader->setFile(new Filesystem());
        $loader->setParserFactory(new DefaultParserFactory());

        return $loader;
    }
}