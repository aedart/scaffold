<?php
namespace Aedart\Scaffold\Traits;

use Composer\Factory;

/**
 * Directories To Index
 *
 * Utility that tells what directories should be indexed by default
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait DirectoriesToIndex
{
    /**
     * Returns a list of directory paths in which scaffold files
     * are to be searched for, in order to build an index
     *
     * @return string[]
     */
    public function directories()
    {
        $composerConfig = Factory::createConfig(null, getcwd());

        $vendorDir = $composerConfig->get('vendor-dir');
        $globalVendorDir = Factory::createConfig(null, $composerConfig->get('home'))->get('vendor-dir');

        return [

            // The "global" vendor directory inside the composer home
            $globalVendorDir,

            // The vendor folder inside the current working directory
            $vendorDir,

            // The current working directory of where this command
            // is being executed from
            getcwd() . DIRECTORY_SEPARATOR,
        ];
    }
}