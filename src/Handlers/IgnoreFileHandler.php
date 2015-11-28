<?php namespace Aedart\Scaffold\Handlers;

/**
 * <h1>Ignore File Handler</h1>
 *
 * Will ignore the given element (file) and do nothing!
 *
 * This type of handler is useful in situations where you
 * need to use ".gitkeep" files in your scaffold  template
 * project, yet you do not wish for them to be copied into
 * the target location
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\FileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class IgnoreFileHandler extends BaseFileHandler{

    public function processElement($element) {
        return;
    }
}