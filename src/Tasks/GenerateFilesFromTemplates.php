<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Collections\Utility\TemplateCollectionParser;

/**
 * Generate Files From Templates
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class GenerateFilesFromTemplates extends BaseTask
{
    use TemplateCollectionParser;

    protected $description = 'Generate files from the template(s)';

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    public function performTask()
    {
        // Check if there are any templates that must be compiled into files...
        $templateKey = 'templates';
        if (!$this->config->has($templateKey) || count($this->config->get($templateKey, [])) == 0) {
            $this->output->note('No templates to process');
            return;
        }

        // Get the templates collection
        $collection = $this->parseTemplatesCollection($this->config->get($templateKey));

        // Process the "templates" - generate files!
        foreach ($collection->all() as $id => $template) {

            // TODO: Fetch handler and process the template...

//            // Get the destination property
//            $property = $template->getDestination();
//
//            // Set the property's id!
//            $property->setId($id);
//
//            // Create the key, where the processed value
//            // will be stored (inside the configuration).
//            $key = $templateKey . '.' . $id . '.destination.value';
//
//            // Get the handler
//            $handler = $this->makePropertyHandler($key);
//
//            // Process the property
//            $handler->processProperty($property);
        }
    }
}
