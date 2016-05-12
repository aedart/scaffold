<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Collections\Utility\PropertiesCollectionParser;
use Aedart\Scaffold\Collections\Utility\TemplateCollectionParser;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler;

/**
 * Generate Files From Templates
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class GenerateFilesFromTemplates extends BaseTask
{
    use PropertiesCollectionParser;
    use TemplateCollectionParser;

    protected $description = 'Builds files based on templates';

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    public function performTask()
    {
        // Get the properties collection
        $templateData = $this->getTemplateData();

        // Check if there are any templates that must be compiled into files...
        $templateKey = 'templates';
        if (!$this->config->has($templateKey) || count($this->config->get($templateKey, [])) == 0) {
            $this->output->note('No templates to process');
            return;
        }

        // Get the templates collection
        $collection = $this->parseTemplatesCollection($this->config->get($templateKey));

        // Fetch the template handler
        $handler = $this->makeTemplateHandler($templateData);

        // Process the "templates" - generate files!
        foreach ($collection->all() as $id => $template) {

            // Process the given template
            $handler->processTemplate($template);
        }
    }

    /**
     * Get the template handler
     *
     * @param TemplateProperties $templateData
     *
     * @return TemplateHandler
     */
    public function makeTemplateHandler(TemplateProperties $templateData)
    {
        return $this->resolveHandler('handlers.template', [
            'templateData'    => $templateData,
        ]);
    }

    /**
     * Get any eventual template data properties
     *
     * @return \Aedart\Scaffold\Contracts\Collections\TemplateProperties
     */
    protected function getTemplateData()
    {
        // Attempt to obtain eventual template data
        $templateDataKey = 'templateData';
        if(!$this->config->has($templateDataKey) || count($this->config->get($templateDataKey, [])) == 0){
            $this->output->note('No template data for templates');
        }

        // Get the properties collection
        return $this->parsePropertiesCollection($this->config->get($templateDataKey));
    }
}
