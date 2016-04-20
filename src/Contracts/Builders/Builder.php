<?php namespace Aedart\Scaffold\Contracts\Builders;

/**
 * Builder
 *
 * A scaffold builder is responsible for creating a
 * specified directory structure, copy files into
 * the project folder and generate files based on
 * available templates.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Builders
 */
interface Builder {

    public function createDirectories();
    public function copyFiles();
    public function generateFiles();
    
}