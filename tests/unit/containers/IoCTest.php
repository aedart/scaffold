<?php

use Aedart\Scaffold\Collections\Directories as DirectoriesCollection;
use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Containers\IoC;
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
     * @covers \Aedart\Scaffold\Providers\ScaffoldServiceProvider::register
     * @covers \Aedart\Scaffold\Providers\ConsoleLoggerServiceProvider::register
     */
    public function canRegisterServiceProviders()
    {
        $ioc = IoC::getInstance();

        // If we can make an instance that we know is
        // registered inside a service provider, then
        // we can assume that everything is in order.
        // NOTE: We cannot / will not test every single
        // binding - we only care about the functionality
        // of invoking "register" on each defined
        // service provider

        $container = $ioc->container();
        $handler = $container->make(Directories::class);

        $this->assertInstanceOf(Directories::class, $handler);
    }

    /**
     * @test
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


    /**
     * @test
     *
     * @covers ::__clone
     *
     * @expectedException \Aedart\Scaffold\Exceptions\ForbiddenException
     */
    public function failsWhenAttemptingToClone()
    {
        $ioc = IoC::getInstance();
        $clone = clone $ioc;
    }

    /**
     * @test
     *
     * @covers ::__wakeup
     *
     * @expectedException \Aedart\Scaffold\Exceptions\ForbiddenException
     */
    public function failsWhenWakeupMethodIsInvoked()
    {
        $ioc = IoC::getInstance();
        $ioc->__wakeup();
    }
}