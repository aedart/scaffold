<?php namespace Aedart\Scaffold;

use Aedart\Scaffold\Console\BuildCommand;
use Aedart\Scaffold\Resolvers\IoC;
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
        BuildCommand::class,
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
        $pathToComposerFile = __DIR__ . '/../composer.json';
        if(!file_exists($pathToComposerFile)){
            return 'UNKNOWN';
        }

        $composer = json_decode(file_get_contents($pathToComposerFile), true);

        return $composer['extra']['branch-alias']['dev-master'];
    }
}