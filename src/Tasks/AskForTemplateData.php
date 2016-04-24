<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Handlers\PropertyHandler;

/**
 * Ask For Template Data Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class AskForTemplateData extends BaseTask
{

    protected $description = 'Processes template data properties';

    /**
     * Performs the actual task execution
     *
     * @return void
     */
    public function performTask()
    {
        // Check if there are any template data properties to ask for...
        $templateDataKey = 'templateData';
        if(!$this->config->has($templateDataKey) || count($this->config->get($templateDataKey, [])) == 0){
            $this->output->note('No template data to process');
            return;
        }

        // Get the properties collection
        $collection = $this->parsePropertiesCollection($this->config->get($templateDataKey));

        // Process the properties
        foreach($collection->all() as $id => $property){
            // Create the key, where the processed value
            // will be stored (inside the configuration).
            $key = $templateDataKey . '.' . $id . '.value';

            // Get the handler
            $handler = $this->getPropertyHandler($key);

            // Process the property
            $handler->processProperty($property);
        }
    }

    /**
     * Parse the given properties list and return a collection
     *
     * @param array $properties
     *
     * @return TemplateProperties
     */
    protected function parsePropertiesCollection(array $properties = [])
    {
        $ioc = IoC::getInstance();

        return $ioc->make(TemplateProperties::class, $properties);
    }

    /**
     * Get the property handler
     *
     * @see \Aedart\Scaffold\Handlers\PropertyHandler
     *
     * @param string $key The "key" or "index" inside the configuration repository,
     *                           in which the final processed value must be stored.
     *
     * @return PropertyHandler
     */
    protected function getPropertyHandler($key)
    {
        return $this->resolveHandler('handlers.property', [
            'config'    => $this->config,
            'key'       => $key,
            'output'    => $this->output
        ]);
    }
}