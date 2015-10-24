<?php namespace Aedart\Scaffold\Engines;

use Aedart\Model\Traits\Arrays\DataTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\TemplateTrait;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigEngine
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Engines
 */
class TwigEngine {

    use DataTrait, BasePathTrait, TemplateTrait;

    /**
     * The engine instance
     *
     * @var Twig_Environment
     */
    protected $twig = null;

    /**************************************************
     * Setup methods
     **************************************************/

    // TODO: PHPDoc
    public function setup() {
        // Set paths
//        $this->templatesDir = Configuration::dataDir() . 'templates';
//        $this->outputDir = Configuration::outputDir();
//        $this->cacheDirectory = $this->outputDir . 'twig/cache';
//
        // New loader instance
        $loader = new Twig_Loader_Filesystem([$this->getBasePath()]);

        // TODO: some "strict" configuration maybe?

        // Twig
        $this->twig = new Twig_Environment($loader, [

        ]);
    }

    /**
     * TODO: PHPDoc ... Exceptions maybe?
     *
     * @return string
     */
    public function render() {
        $template = $this->twig->loadTemplate($this->getTemplate());

        return $template->render($this->getData());
    }
}