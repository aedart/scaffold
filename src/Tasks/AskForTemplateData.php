<?php namespace Aedart\Scaffold\Tasks;

use Aedart\Scaffold\Containers\IoC;
use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Illuminate\Contracts\Config\Repository;

/**
 * Ask For Template Data Task
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Tasks
 */
class AskForTemplateData extends BaseTask
{

    protected $description = 'Processes template data';

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
            // Process the value (ask for it if needed)
            $value = $this->processProperty($property);

            // Output what value is being used
            // TODO: Do NOT display value, if type is hidden!
            // TODO: Display 'true / false' for boolean values
            $this->output->text("Using <info>{$value}</info> for <comment>{$id}</comment>");
        }
    }

    // TODO: Implement, test and PHPDoc...
    protected function processProperty(Property $property)
    {
        $type = $property->getType();
        $finalValue = null;

        switch($type){
            case Type::VALUE:
                $finalValue = $property->getValue();
                break;

            case Type::QUESTION:
                // TODO: Implement validation
                $finalValue = $this->output->ask($property->getQuestion(), $property->getValue(), null);
                break;

            case Type::CHOICE:
                $finalValue = $this->output->choice($property->getQuestion(), $property->getChoices(), $property->getValue());
                break;

            case Type::CONFIRM:
                $finalValue = $this->output->confirm($property->getQuestion(), $property->getValue());
                break;

            case Type::HIDDEN:
                // TODO: Implement validation

                // TODO: Hidden should always be confirmed - usage most likely for passwords...

                $finalValue = $this->output->askHidden($property->getQuestion(), null);
                break;

            default:
                // TODO: Fail here...
                $this->output->warning("throw exception for '{$property->getId()}', type {$property->getType()} is unsupported");
                break;
        }

        // TODO: Implement "post process" of value

        // Finally, return the final value
        return $finalValue;
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
}