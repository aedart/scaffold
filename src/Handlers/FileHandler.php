<?php namespace Aedart\Scaffold\Handlers;
use Aedart\Laravel\Helpers\Traits\Config\ConfigTrait;
use Aedart\Scaffold\Engines\TwigEngine;

/**
 * Class FileHandler
 *
 * TODO: Refactor, break it down into interface, smaller components, traits, etc.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class FileHandler {

    use ConfigTrait;

    public function handle($element) {
        $engine = $this->getTemplateEngine();

        // Set the base path
        $engine->setBasePath($this->getBasePath());

        // Set the template to be rendered
        $engine->setTemplateFile($element);

        // Parse the data
        $engine->setData($this->processData($this->getData()));

        // Setup the actual engine
        $engine->setup();

        // Render the template
        $renderedTemplate = $engine->render();

        //dd($renderedTemplate);

        // TODO: Output path needs to be relative-ish... match structure of inside the "templates" folder
        // TODO: If target folder-path doesn't exist, then file_put_content fails

        // TODO: tip;
        //        (http://php.net/manual/en/function.file-put-contents.php#84180)
        //        File put contents fails if you try to put a file in a directory that doesn't exist. This creates the directory.
        //
        //
        //    function file_force_contents($dir, $contents){
        //        $parts = explode('/', $dir);
        //        $file = array_pop($parts);
        //        $dir = '';
        //        foreach($parts as $part)
        //            if(!is_dir($dir .= "/$part")) mkdir($dir);
        //        file_put_contents("$dir/$file", $contents);
        //    }
        //

        $result = file_put_contents($this->getOutputPath() . $this->getFilename(), $renderedTemplate);
    }

    // TODO: PHPDoc .... and why was this needed?
    public function processData(array $data) {
        if(empty($data)){
            return [];
        }

        $output = [];

        foreach($data as $key => $value){
            // TODO: Not fail safe enough - might not contain a 'default'
            $output[$key] = $value['default'];
        }

        return $output;
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
        return $this->getConfig()->get('scaffold.basePath', null);
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

    /**************************************************
     * TODO: Data - split into trait
     **************************************************/

    /**
     * Data array - contains various types of data entries
     *
     * @var array|null
     */
    protected $data = null;

    /**
     * Set the given data
     *
     * @param array $data Data array - contains various types of data entries
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
        return $this->getConfig()->get('scaffold.data', null);
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
     * TODO: Output Path - split into trait
     **************************************************/

    /**
     * Output path - location of where something needs to be persisted
     *
     * @var string|null
     */
    protected $outputPath = null;

    /**
     * Set the given output path
     *
     * @param string $path Output path - location of where something needs to be persisted
     *
     * @return void
     */
    public function setOutputPath($path) {
        $this->outputPath = $path;
    }

    /**
     * Get the given output path
     *
     * If no output path has been set, this method will
     * set and return a default output path, if any such
     * value is available
     *
     * @see getDefaultOutputPath()
     *
     * @return string|null output path or null if none output path has been set
     */
    public function getOutputPath() {
        if (!$this->hasOutputPath() && $this->hasDefaultOutputPath()) {
            $this->setOutputPath($this->getDefaultOutputPath());
        }
        return $this->outputPath;
    }

    /**
     * Get a default output path value, if any is available
     *
     * @return string|null A default output path value or Null if no default value is available
     */
    public function getDefaultOutputPath() {
        return null;
    }

    /**
     * Check if output path has been set
     *
     * @return bool True if output path has been set, false if not
     */
    public function hasOutputPath() {
        return !is_null($this->outputPath);
    }

    /**
     * Check if a default output path is available or not
     *
     * @return bool True of a default output path is available, false if not
     */
    public function hasDefaultOutputPath() {
        return !is_null($this->getDefaultOutputPath());
    }

    /**************************************************
     * TODO: file name - split into trait, facade... etc
     **************************************************/

    /**
     * Filename
     *
     * @var string|null
     */
    protected $filename = null;

    /**
     * Set the given filename
     *
     * @param string $name Filename
     *
     * @return void
     */
    public function setFilename($name) {
        $this->filename = $name;
    }

    /**
     * Get the given filename
     *
     * If no filename has been set, this method will
     * set and return a default filename, if any such
     * value is available
     *
     * @see getDefaultFilename()
     *
     * @return string|null filename or null if none filename has been set
     */
    public function getFilename() {
        if (!$this->hasFilename() && $this->hasDefaultFilename()) {
            $this->setFilename($this->getDefaultFilename());
        }
        return $this->filename;
    }

    /**
     * Get a default filename value, if any is available
     *
     * @return string|null A default filename value or Null if no default value is available
     */
    public function getDefaultFilename() {
        return null;
    }

    /**
     * Check if filename has been set
     *
     * @return bool True if filename has been set, false if not
     */
    public function hasFilename() {
        return !is_null($this->filename);
    }

    /**
     * Check if a default filename is available or not
     *
     * @return bool True of a default filename is available, false if not
     */
    public function hasDefaultFilename() {
        return !is_null($this->getDefaultFilename());
    }

    /**************************************************
     * TODO: template Engine - split into trait, facade... etc
     **************************************************/

    /**
     * Template Engine
     *
     * @var TwigEngine|null
     */
    protected $templateEngine = null;

    /**
     * Set the given template engine
     *
     * @param TwigEngine $engine Template Engine
     *
     * @return void
     */
    public function setTemplateEngine(TwigEngine $engine) {
        $this->templateEngine = $engine;
    }

    /**
     * Get the given template engine
     *
     * If no template engine has been set, this method will
     * set and return a default template engine, if any such
     * value is available
     *
     * @see getDefaultTemplateEngine()
     *
     * @return TwigEngine|null template engine or null if none template engine has been set
     */
    public function getTemplateEngine() {
        if (!$this->hasTemplateEngine() && $this->hasDefaultTemplateEngine()) {
            $this->setTemplateEngine($this->getDefaultTemplateEngine());
        }
        return $this->templateEngine;
    }

    /**
     * Get a default template engine value, if any is available
     *
     * @return TwigEngine|null A default template engine value or Null if no default value is available
     */
    public function getDefaultTemplateEngine() {
        // TODO: This is too hard-coded, should be able to swap this dependency
        return new TwigEngine();
    }

    /**
     * Check if template engine has been set
     *
     * @return bool True if template engine has been set, false if not
     */
    public function hasTemplateEngine() {
        return !is_null($this->templateEngine);
    }

    /**
     * Check if a default template engine is available or not
     *
     * @return bool True of a default template engine is available, false if not
     */
    public function hasDefaultTemplateEngine() {
        return !is_null($this->getDefaultTemplateEngine());
    }
}