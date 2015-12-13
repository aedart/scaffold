<?php namespace Aedart\Scaffold\Builders;

use Aedart\Scaffold\Contracts\Data\Scaffold;
use Aedart\Scaffold\Contracts\Handlers\DirectoryHandler;
use Aedart\Scaffold\Contracts\Handlers\FileHandler;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;

/**
 * Class Builder
 *
 * TODO.... how should the process of building "something" be?
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Builders
 */
class Builder {

    public function build(Scaffold $scaffold) {
        // Set base path

        // Set output path

        // Make directory handler
            // Configure directory handler
            // Build directories using configured directories handler

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

        // Throw "unable to build" exception, in case of anything goes wrong
    }

    public function buildDirectories(DirectoryHandler $directoryHandler) {

    }

    // TODO: Data structure of Scaffold should include "files collection" to make it easier to process!
    public function buildFiles(FileHandler $fileHandler) {

    }

    public function buildTemplates(TemplateHandler $templateHandler) {

    }

    // return Handler
    public function makeHandler($handlerClassPath) {

    }
}