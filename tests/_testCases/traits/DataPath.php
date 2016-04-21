<?php

use Codeception\Configuration;

/**
 * Data Path
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait DataPath
{

    /**
     * Returns the path to the test data directory
     *
     * @return string
     */
    public function dataPath()
    {
        return Configuration::dataDir();
    }

}