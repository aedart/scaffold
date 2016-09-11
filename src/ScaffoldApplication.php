<?php namespace Aedart\Scaffold;

use Aedart\Installed\Version\Reader;
use Aedart\Scaffold\Console\BuildCommand;
use Aedart\Scaffold\Console\IndexCommand;
use Aedart\Scaffold\Console\InstallCommand;
use Aedart\Scaffold\Containers\IoC;
use Symfony\Component\Console\Application;

/**
 * Scaffold Application
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold
 */
class ScaffoldApplication extends Application
{
    /**
     * List of this application's commands
     *
     * @var string[]
     */
    protected $appCommands = [
        InstallCommand::class,
        BuildCommand::class,
        IndexCommand::class
    ];

    /**
     * Scaffold Application constructor.
     */
    public function __construct()
    {
        $name = 'Aedart Scaffold Installer';
        $version = $this->resolveApplicationVersion();

        parent::__construct($name, $version);

        $this->setupApplication();
    }

    /**
     * Setup this application
     */
    protected function setupApplication()
    {
        $this->bootIoC();
        $this->registerCommands();
    }

    /**
     * Boot the IoC
     */
    protected function bootIoC()
    {
        IoC::boot();
    }

    /**
     * Register this application's console commands
     */
    protected function registerCommands()
    {
        foreach($this->appCommands as $command){
            $this->add( new $command);
        }
    }

    /**
     * Returns this application's version from it's
     * package composer file
     *
     * @return string Package version or UNKNOWN if not able to read composer file
     */
    protected function resolveApplicationVersion()
    {
        return (new Reader())->getVersion('aedart/scaffold');
    }
}