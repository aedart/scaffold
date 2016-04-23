<?php

use Aedart\Scaffold\Containers\IoC;

/**
 * IoC Destroyer
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait IoCDestroyer
{

    /**
     * Destroy the IoC
     *
     * This might be needed in some situations where
     * tests are expected to behave in certain ways,
     * whenever an IoC has not registered specific
     * providers or able to resolve instances...
     */
    public function destroyIoC()
    {
        $ioc = IoC::getInstance();
        $ioc->destroy();
    }
}