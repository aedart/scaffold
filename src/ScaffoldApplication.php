<?php namespace Aedart\Scaffold;

use Aedart\Scaffold\Console\BuildCommand;
use Aedart\Scaffold\Console\IndexCommand;
use Aedart\Scaffold\Console\InstallCommand;
use Aedart\Scaffold\Containers\IoC;
use Symfony\Component\Console\Application;
use Composer\Factory;

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
        // TODO: This needs to change, because this a misuse of the "branch-alias"
        // TODO: A safer choice would be to create rely on "git describe --tags"
        // TODO:  a) create a script that can run on composer update
        // TODO:        - Fetch version via git. If it's not clean, e.g. x.y.z, then  fallback
        // TODO:  b) Fallback on readying composer's branch alias
        // TODO:  c) create a version.txt file
        // TODO:  d) load version from that file...

        $pathToComposerFile = __DIR__ . '/../composer.json';
        if(!file_exists($pathToComposerFile)){
            return 'UNKNOWN';
        }

        $composer = json_decode(file_get_contents($pathToComposerFile), true);

        return $composer['extra']['branch-alias']['dev-master'];
    }
}