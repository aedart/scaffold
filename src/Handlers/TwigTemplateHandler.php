<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;
use Aedart\Scaffold\Contracts\Templates\Template;
use Aedart\Scaffold\Exceptions\CannotProcessTemplateException;
use Aedart\Scaffold\Templates\TemplateData;
use Exception;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Twig Template Handler
 *
 * This handler generates files from a given template, based on
 * the Twig Template Engine
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\TemplateHandler
 * @see \Twig_Environment
 *
 * @see http://twig.sensiolabs.org/
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class TwigTemplateHandler extends BaseHandler implements TemplateHandler
{
    use TemplateData;

    /**
     * The Template Engine
     *
     * @var Twig_Environment
     */
    protected $engine = null;

    /**
     * Template engine options
     *
     * @var array
     */
    protected $engineOptions = [
        'strict_variables'      => true,
    ];

    /**
     * Perform the actual element processing
     *
     * @param mixed $element
     *
     * @return void
     */
    public function processElement($element)
    {
        $this->setupEngine();
        $this->generateFileFrom($element);
    }

    /**
     * Setup the template engine
     */
    protected function setupEngine()
    {
        // New loader instance, set the base path
        $loader = new Twig_Loader_Filesystem([$this->getBasePath()]);

        // Create new instance of the Twig engine
        $this->engine = new Twig_Environment($loader, $this->getEngineOptions());
    }

    /**
     * Returns template engine options
     *
     * @return array
     */
    protected function getEngineOptions()
    {
        return $this->engineOptions;
    }

    /**
     * Renders the given template and returns content
     *
     * @param string $templatePath Path to the template
     * @param array $data [optional] Context data to be assigned to the template
     *
     * @return string Rendered template content
     */
    protected function renderTemplate($templatePath, array $data = [])
    {
        return $this->engine
            ->loadTemplate($templatePath)
            ->render($data);
    }

    /**
     * Parse the given collection into an array, which can be
     * assigned to a template.
     *
     * @param TemplateProperties $collection
     *
     * @return array
     */
    protected function parseTemplateDataToArray(TemplateProperties $collection)
    {
        $output = [];

        foreach($collection->all() as $id => $property){
            $output[$id] = $property->getValue();
        }

        return $output;
    }

    /**
     * Generate a file based on the given template.
     *
     * If this handler has any template data set, it will
     * be assigned to the given template.
     *
     * @see Template
     * @see getTemplateData()
     *
     * @param Template $template The template to use for generating a file
     *
     * @throws CannotProcessTemplateException If (a) template's source does not exist,
     * (b) a file already exists at the template's destination, or (c) unable to
     * generate the file.
     */
    protected function generateFileFrom(Template $template)
    {
        // Set a few variables...
        $fs = $this->getFile();
        $templatePath = $this->getBasePath() . $template->getSource();
        $destination = $this->outputPath . $template->getDestination()->getValue();
        $data = [];

        // Fail if source template does not exist
        if(!$fs->exists($templatePath)){
            throw new CannotProcessTemplateException(sprintf('Template %s does not exist', $templatePath));
        }

        // Fail if destination file already exists
        if($fs->exists($destination)){
            throw new CannotProcessTemplateException(sprintf('File %s already exists, will not overwrite!', $destination));
        }

        // Parse template data properties to array, if any available
        if($this->hasTemplateData()){
            $data = $this->parseTemplateDataToArray($this->getTemplateData());
        }

        // Finally, generate the file...
        $this->generateFile($templatePath, $destination, $data);
    }

    /**
     * Generates a file at given destination, based on the
     * given template and context data
     *
     * @param string $templatePath Path to the template
     * @param string $destination Location and file name to be generated
     * @param array $data [optional] Context data to be assigned to the template
     *
     * @throws CannotProcessTemplateException If unable to render template or write file to
     * the disk
     */
    protected function generateFile($templatePath, $destination, array $data = [])
    {
        try {
            // Render the template
            $content = $this->renderTemplate($templatePath, $data);

            // Write the file
            $bytes = $this->getFile()->append($destination, $content);

            // Check if file was written
            if($bytes === false){
                throw new \RuntimeException(sprintf('Unable to write %s to the disk. Please check your permissions!', $destination));
            }
        } catch (Exception $e){
            throw new CannotProcessTemplateException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Process the given template - compile and generate a file
     * based on the given template
     *
     * @see Template
     *
     * @param Template $template The template to use in order to build a file
     *
     * @return void
     *
     * @throws CannotProcessTemplateException
     */
    public function processTemplate(Template $template)
    {
        $this->process($template);
    }
}