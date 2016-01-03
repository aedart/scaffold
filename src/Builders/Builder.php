<?php namespace Aedart\Scaffold\Builders;

use Aedart\Laravel\Helpers\Traits\Config\ConfigTrait;
use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\OutputPathTrait;
use Aedart\Scaffold\Contracts\Data\Scaffold;
use Aedart\Scaffold\Contracts\Handlers\DirectoryHandler;
use Aedart\Scaffold\Contracts\Handlers\FileHandler;
use Aedart\Scaffold\Contracts\Handlers\Handler;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Exceptions\CannotBuildScaffoldException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Builder
 *
 * TODO.... how should the process of building "something" be?
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Builders
 */
class Builder {

    use BasePathTrait,
        OutputPathTrait,
        ConfigTrait,
        FileTrait;

    /**
     * Builder constructor.
     *
     * @param Repository|null $configuration
     * @param Filesystem $filesystem
     */
    public function __construct(Repository $configuration = null, Filesystem $filesystem = null) {
        if(!is_null($configuration)){
            $this->setConfig($configuration);
        }
        if(!is_null($filesystem)){
            $this->setFile($filesystem);
        }
    }

    /********************************************************************
     * Build methods
     *******************************************************************/

    /**
     * Build the given scaffold
     *
     * @param Scaffold $scaffold The scaffold to build
     * @param string $outputPath Output of where the scaffold directories and files are to be created
     *
     * @throws CannotBuildScaffoldException In case the scaffold cannot be build
     */
    public function build(Scaffold $scaffold, $outputPath) {
        try {
            $this->doBuild($scaffold, $outputPath);
        } catch(\Exception $e){
            throw new CannotBuildScaffoldException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Performs the actual scaffold build
     *
     * @param Scaffold $scaffold The scaffold to build
     * @param string $outputPath Output of where the scaffold directories and files are to be created
     */
    protected function doBuild(Scaffold $scaffold, $outputPath){
        // Set base path
        $this->setBasePath($scaffold->getBasePath());

        // Set output path
        $this->setOutputPath($outputPath);

        // Build directories
        $this->resolveDirectories($scaffold);

        // Find all files in base path
        // Find corresponding "file" in scaffold based on filename / id
        // Make the desired file handler
        // Configure the file handler
        // Build file using the configured file handler

        // (Re)Find all files (templates) in base path ?
        // Find corresponding "templates" in scaffold based on filename / id
        // Make desired template handler
        // Configure template handler
        // Build file using configured template handler
    }

    /**
     * Builds the directories from the "root" (base path) level of the scaffold
     *
     * @param DirectoryHandler $directoryHandler
     */
    public function buildDirectories(DirectoryHandler $directoryHandler) {
        // Build directory from the "root" (getBasePath) level
        $directoryHandler->handle('');
    }

    // TODO: Data structure of Scaffold should include "files collection" to make it easier to process!
    public function buildFiles(FileHandler $fileHandler) {

    }

    public function buildTemplates(TemplateHandler $templateHandler) {

    }

    /********************************************************************
     * Resolve methods
     *******************************************************************/

    /**
     * Creates directory handler and then uses it to build the
     * scaffold's directory structure - if any
     *
     * @param Scaffold $scaffold
     */
    protected function resolveDirectories(Scaffold $scaffold){
        // Make directory handler
        $handler = $this->makeDirectoryHandler($scaffold->getDirectoryHandler());

        // Build directories
        $this->buildDirectories($handler);
    }

    /********************************************************************
     * Make handlers methods
     *******************************************************************/

    /**
     * Make a new instance of the given handler and configure it
     *
     * @param string $handlerClassPath Path of the handler to make a new instance of
     *
     * @return Handler
     */
    public function makeHandler($handlerClassPath) {
        $handler = new $handlerClassPath();

        $handler->setBasePath($this->getBasePath());
        $handler->setOutputPath($this->getOutputPath());
        $handler->setFile($this->getFile());
        $handler->setConfig($this->getConfig());

        return $handler;
    }

    /**
     * Make a new instance of the given directory handler and configure it
     *
     * @param string $classPath Path of the directory handler to make
     *
     * @return DirectoryHandler
     */
    protected function makeDirectoryHandler($classPath){
        return $this->makeHandler($classPath);
    }
}