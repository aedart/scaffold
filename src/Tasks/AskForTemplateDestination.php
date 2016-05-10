<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Collections\Utility\TemplateCollectionParser;
use Aedart\Scaffold\Handlers\Utility\PropertyHandlerResolver;

/**
 * Ask For Template Destination
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class AskForTemplateDestination extends BaseTask
{
    use PropertyHandlerResolver;
    use TemplateCollectionParser;

    protected $description = 'Processes destination properties for template(s)';

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    public function performTask()
    {
        // Check if there are any templates, which might need destinations...
        $templateKey = 'templates';
        if(!$this->config->has($templateKey) || count($this->config->get($templateKey, [])) == 0){
            $this->output->note('No templates to process');
            return;
        }

        // Get the templates collection
        $collection = $this->parseTemplatesCollection($this->config->get($templateKey));

        // Process the "destination" properties for each template
        foreach($collection->all() as $id => $template){
            // Get the destination property
            $property = $template->getDestination();

            // Set the property's id!
            $property->setId($id);

            // Create the key, where the processed value
            // will be stored (inside the configuration).
            $key = $templateKey . '.' . $id . '.destination.value';

            // Get the handler
            $handler = $this->makePropertyHandler($key);

            // Process the property
            $handler->processProperty($property);
        }
    }
}