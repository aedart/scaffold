<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Laravel\Helpers\Traits\Config\ConfigTrait;
use Aedart\Model\Traits\Arrays\DataTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\FilenameTrait;
use Aedart\Model\Traits\Strings\OutputPathTrait;
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

    use ConfigTrait,
        BasePathTrait,
        DataTrait,
        OutputPathTrait,
        FilenameTrait;

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

    /**
     * Get a default base path value, if any is available
     *
     * @return string|null A default base path value or Null if no default value is available
     */
    public function getDefaultBasePath() {
        return $this->getConfig()->get('scaffold.basePath', null);
    }

    /**
     * Get a default data value, if any is available
     *
     * @return array|null A default data value or Null if no default value is available
     */
    public function getDefaultData() {
        return $this->getConfig()->get('scaffold.data', null);
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