<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\Directories;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler as DirHandler;
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
     * Instance of a directory handler
     *
     * @var \Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler|null
     */
    protected $directoryHandler = null;

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
     * {@inheritdoc}
     */
    public function populate(array $data = []) : void
    {
        if(isset($data['directoryHandler']) && $data['directoryHandler'] instanceof DirHandler){
            $this->directoryHandler = $data['directoryHandler'];
        }

        if(isset($data['templateData']) && $data['templateData'] instanceof TemplateProperties){
            $this->setTemplateData($data['templateData']);
        }
    }


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

    /**
     * Setup the template engine
     */
    protected function setupEngine()
    {
        // New loader instance, set the base path
        $loader = new Twig_Loader_Filesystem($this->getBasePath());

        // Create new instance of the Twig engine
        $this->engine = new Twig_Environment($loader, $this->getEngineOptions());
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
        $log = $this->getLog();
        $fs = $this->getFile();
        $source = $template->getSource();
        $destination = $this->outputPath . $template->getDestination()->getValue();

        // Fail if source template does not exist
        if(!$fs->exists($this->getBasePath() . $source)){
            throw new CannotProcessTemplateException(sprintf('Template %s does not exist', $source));
        }

        // Abort if destination file already exists
        if($fs->exists($destination)){
            $log->notice('skipped "{templateId}", file already exists in "{destination}"', ['templateId' => $template->getId(), 'destination' => $destination]);
            return;
        }

        // Parse template data properties to array, if any available
        $data = $this->prepareTemplateData($template->getId(), $this->getBasePath() . $source, $destination);

        // Finally, generate the file...
        $this->generateFile($source, $destination, $data);

        // Finally, log the output
        $log->info('generated "{path}" for {templateId} template', ['path' => $destination, 'templateId' => $template->getId()]);
    }

    /**
     * Generates a file at given destination, based on the
     * given template and context data
     *
     * @param string $source Path to the template
     * @param string $destination Location and file name to be generated
     * @param array $data [optional] Context data to be assigned to the template
     *
     * @throws CannotProcessTemplateException If unable to render template or write file to
     * the disk
     */
    protected function generateFile($source, $destination, array $data = [])
    {
        try {
            // Render the template
            $content = $this->renderTemplate($source, $data);

            // Write the file
            $this->writeFile($destination, $content);
        } catch (Exception $e){
            throw new CannotProcessTemplateException($e->getMessage(), $e->getCode(), $e);
        }
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
     * Write the content to the given file
     *
     * If the destination directory does not exist, it
     * will be created.
     *
     * @see prepareDestinationPath()
     *
     * @param string $destination
     * @param string $content
     *
     * @throws \RuntimeException If unable to write content to file
     */
    protected function writeFile($destination, $content)
    {
        // Prepare the path
        $this->prepareDestinationPath(pathinfo($destination, PATHINFO_DIRNAME));

        // Actual writing of file
        $bytes = $this->getFile()->append($destination, $content);

        // Check if file was written
        if($bytes === false){
            throw new \RuntimeException(sprintf('Unable to write %s to the disk. Please check your permissions!', $destination));
        }
    }

    /**
     * Creates the destination directory, if it does not
     * already exist
     *
     * @param string $destination
     */
    protected function prepareDestinationPath($destination)
    {
        // Before a file is generated, we need to make sure
        // that it's destination directory exists. If not,
        // then we need to make sure that it is created or
        // the end-user will get frustrated that this handler
        // doesn't take care of it automatically. However, skip
        // if already exists - no need to process this via the
        // directory handler
        if($this->getFile()->exists($destination)){
            return;
        }

        // We could do simply use the file system (see getFile()),
        // to check and create the directory. However, in case
        // that the end-user specified a different handler for
        // making directories, which follows a different set of
        // rules, e.g. the access mode, then it would be nice
        // if this handler could re-use that and avoid dual
        // implementation.

        // Remove the "output path" from the destination, before
        // passing it further to the directories handler, which
        // automatically ensures to apply the output path!
        $destination = str_replace($this->getOutputPath(), '', $destination);

        // Configure the directory handler - this needs to be
        // done here, because the file system, logger and paths
        // might change per iteration
        $this->configureDirectoryHandler();

        /** @var Directories $collection */
        $collection = IoC::getInstance()->make(Directories::class);
        $collection->populate($destination);

        // Process the destination directory
        $this->directoryHandler->processDirectories($collection);
    }

    /**
     * Prepare the template data, for the given template
     *
     * Method assigns any available Template Data Properties
     * to an array, as well as the given template's source
     * and destination.
     *
     * @param string $templateId Id of the template
     * @param string $source The source location of the template
     * @param string $destination The destination of the file to be generated
     *
     * @return array
     */
    protected function prepareTemplateData($templateId, $source, $destination)
    {
        $data = [];

        if($this->hasTemplateData()){
            $data = $this->parseTemplateDataToArray($this->getTemplateData());
        }

        // Assign source and destination to data
        $data['template'] = [
            $templateId => [
                'source'        => $source,
                'destination'   => $destination
            ]
        ];

        return $data;
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
     * Returns template engine options
     *
     * @return array
     */
    protected function getEngineOptions()
    {
        return $this->engineOptions;
    }

    /**
     * Configure the directory handler
     */
    protected function configureDirectoryHandler()
    {
        $this->directoryHandler->setFile($this->getFile());
        $this->directoryHandler->setLog($this->getLog());
        $this->directoryHandler->setBasePath($this->getBasePath());
        $this->directoryHandler->setOutputPath($this->getOutputPath());
    }
}