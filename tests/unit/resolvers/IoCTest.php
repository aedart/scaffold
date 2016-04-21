<?php

use Aedart\Scaffold\Collections\Directories as DirectoriesCollection;
use Aedart\Scaffold\Contracts\Collections\Directories;
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
     * @covers ::resolveHandler
     * @covers ::resolveFromConfig
     */
    public function canResolveAndConfigureAHandler()
    {
        $ioc = IoC::getInstance();

        $config = new Repository([
            'basePath' => getcwd(),
            'outputPath' => getcwd() . 'tests',
            'handlers' => [
                'directory' => DirectoriesHandler::class
            ]
        ]);

        /** @var \Aedart\Scaffold\Contracts\Handlers\Handler $handler */
        $handler = $ioc->resolveHandler('handlers.directory', $config);

        $this->assertInstanceOf(DirectoriesHandler::class, $handler, 'Incorrect handler instance');
        $this->assertSame($config->get('basePath'), $handler->getBasePath(), 'Incorrect Base Path set');
        $this->assertSame($config->get('outputPath'), $handler->getOutputPath(), 'Incorrect Output Path set');
    }

    /**
     * @test
     *
     * @covers ::make
     */
    public function canMakeInstance()
    {
        $ioc = IoC::getInstance();

        $handler = $ioc->make(Directories::class, $this->makeFolderPaths());

        $this->assertInstanceOf(DirectoriesCollection::class, $handler);
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