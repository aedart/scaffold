<?php namespace Aedart\Scaffold\Engines;

use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigEngine
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Engines
 */
class TwigEngine {

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
     * TODO: Data - split into trait
     **************************************************/

    /**
     * Data to be parsed and rendered by the template
     *
     * @var array|null
     */
    protected $data = null;

    /**
     * Set the given data
     *
     * @param array $data Data to be parsed and rendered by the template
     *
     * @return void
     */
    public function setData(array $data) {
        $this->data = $data;
    }

    /**
     * Get the given data
     *
     * If no data has been set, this method will
     * set and return a default data, if any such
     * value is available
     *
     * @see getDefaultData()
     *
     * @return array|null data or null if none data has been set
     */
    public function getData() {
        if (!$this->hasData() && $this->hasDefaultData()) {
            $this->setData($this->getDefaultData());
        }
        return $this->data;
    }

    /**
     * Get a default data value, if any is available
     *
     * @return array|null A default data value or Null if no default value is available
     */
    public function getDefaultData() {
        return null;
    }

    /**
     * Check if data has been set
     *
     * @return bool True if data has been set, false if not
     */
    public function hasData() {
        return !is_null($this->data);
    }

    /**
     * Check if a default data is available or not
     *
     * @return bool True of a default data is available, false if not
     */
    public function hasDefaultData() {
        return !is_null($this->getDefaultData());
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

    /**************************************************
     * TODO: Base Path - split into trait
     **************************************************/

    /**
     * Base Path; location of where the templates are located
     *
     * @var string|null
     */
    protected $basePath = null;

    /**
     * Set the given base path
     *
     * @param string $path Base Path; location of where the templates are located
     *
     * @return void
     */
    public function setBasePath($path) {
        $this->basePath = $path;
    }

    /**
     * Get the given base path
     *
     * If no base path has been set, this method will
     * set and return a default base path, if any such
     * value is available
     *
     * @see getDefaultBasePath()
     *
     * @return string|null base path or null if none base path has been set
     */
    public function getBasePath() {
        if (!$this->hasBasePath() && $this->hasDefaultBasePath()) {
            $this->setBasePath($this->getDefaultBasePath());
        }
        return $this->basePath;
    }

    /**
     * Get a default base path value, if any is available
     *
     * @return string|null A default base path value or Null if no default value is available
     */
    public function getDefaultBasePath() {
        return null;
    }

    /**
     * Check if base path has been set
     *
     * @return bool True if base path has been set, false if not
     */
    public function hasBasePath() {
        return !is_null($this->basePath);
    }

    /**
     * Check if a default base path is available or not
     *
     * @return bool True of a default base path is available, false if not
     */
    public function hasDefaultBasePath() {
        return !is_null($this->getDefaultBasePath());
    }
}