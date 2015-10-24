<?php namespace Aedart\Scaffold\Engines;

use Aedart\Model\Traits\Arrays\DataTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigEngine
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Engines
 */
class TwigEngine {

    use DataTrait, BasePathTrait;

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
        $template = $this->twig->loadTemplate($this->getTemplateFile());

        return $template->render($this->getData());
    }

    /**************************************************
     * TODO: Template file - split into trait
     **************************************************/

    /**
     * Template File - location of a given template's path
     *
     * @var string|null
     */
    protected $templateFile = null;

    /**
     * Set the given template file
     *
     * @param string $path Template File - location of a given template's path
     *
     * @return void
     */
    public function setTemplateFile($path) {
        $this->templateFile = $path;
    }

    /**
     * Get the given template file
     *
     * If no template file has been set, this method will
     * set and return a default template file, if any such
     * value is available
     *
     * @see getDefaultTemplateFile()
     *
     * @return string|null template file or null if none template file has been set
     */
    public function getTemplateFile() {
        if (!$this->hasTemplateFile() && $this->hasDefaultTemplateFile()) {
            $this->setTemplateFile($this->getDefaultTemplateFile());
        }
        return $this->templateFile;
    }

    /**
     * Get a default template file value, if any is available
     *
     * @return string|null A default template file value or Null if no default value is available
     */
    public function getDefaultTemplateFile() {
        return null;
    }

    /**
     * Check if template file has been set
     *
     * @return bool True if template file has been set, false if not
     */
    public function hasTemplateFile() {
        return !is_null($this->templateFile);
    }

    /**
     * Check if a default template file is available or not
     *
     * @return bool True of a default template file is available, false if not
     */
    public function hasDefaultTemplateFile() {
        return !is_null($this->getDefaultTemplateFile());
    }
}