<?php

use Codeception\Configuration;
use Codeception\Util\Debug;

/**
 * Output Path
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait OutputPath
{
    /**
     * Creates an output path, if it does not already exist
     */
    public function createOutputPath()
    {
        if(!file_exists($this->outputPath())){
            mkdir($this->outputPath(), 0755, true);

            Debug::debug(sprintf('<info>Created output path </info><debug>%s</debug>', $this->outputPath()));
        }
    }

    /**
     * Returns the output directory path
     *
     * @return string
     * @throws \Codeception\Exception\ConfigurationException
     */
    public function outputPath()
    {
        return Configuration::outputDir();
    }
}