<?php

use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Resolvers\IoC;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\Container;

/**
 * Class IoCTest
 *
 * @group resolvers
 * @group ioc
 *
 * @coversDefaultClass Aedart\Scaffold\Resolvers\IoC
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class IoCTest extends BaseUnitTest
{
    protected function _after()
    {
        $ioc = IoC::getInstance();
        $ioc->destroy();

        parent::_after();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::getInstance
     * @covers ::boot
     * @covers ::__construct
     * @covers ::setupContainer
     */
    public function canObtainInstance()
    {
        //$ioc = IoC::getInstance();
        $ioc = IoC::boot();

        $this->assertNotNull($ioc);
    }

    /**
     * @test
     *
     * @covers ::container
     */
    public function canObtainServiceContainer()
    {
        $ioc = IoC::getInstance();

        $container = $ioc->container();

        $this->assertInstanceOf(Container::class, $container);
    }

    /**
     * @test
     *
     * @covers ::registerServiceProviders
     *
     * @covers ::resolveFromConfig
     */
    public function canResolveFromServiceProvider()
    {
        $ioc = IoC::getInstance();

        $handler = $ioc->resolveFromConfig('handlers.directory', new Repository([]));

        $this->assertNotNull($handler);
    }

    /**
     * @test
     *
     * @covers ::registerServiceProviders
     *
     * @covers ::resolveFromConfig
     */
    public function canResolveFromConfig()
    {
        $ioc = IoC::getInstance();

        $handler = $ioc->resolveFromConfig('handlers.directory', new Repository([
            'handlers' => [
                'directory' => DirectoriesHandler::class
            ]
        ]));

        $this->assertInstanceOf(DirectoriesHandler::class, $handler);
    }

    /**
     * @test
     *
     * @covers ::destroy
     */
    public function canDestroyInstance()
    {
        $ioc = IoC::getInstance();
        $ioc->destroy();

        $this->assertNull($ioc->container());
    }
}