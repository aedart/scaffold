<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Scaffold\Contracts\Handlers\PropertyHandler as PropertyHandlerInterface;
use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Aedart\Scaffold\Exceptions\CannotProcessPropertyException;
use Illuminate\Contracts\Config\Repository;
use Symfony\Component\Console\Style\StyleInterface;

/**
 * Property Handler
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\PropertyHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class PropertyHandler extends BaseHandler implements PropertyHandlerInterface
{
    /**
     * The configuration repository
     *
     * @var Repository
     */
    protected $config;

    /**
     * The "key" or "index" inside the
     * configuration repository
     *
     * @see $config
     *
     * @var string
     */
    protected $key;

    /**
     * The console output
     *
     * @var StyleInterface
     */
    protected $output;

    /**
     * Property Handler constructor.
     *
     * @param Repository $config The configuration repository where to store the property's
     *                           processed value.
     * @param string $key The "key" or "index" inside the configuration repository,
     *                           in which the final processed value must be stored.
     * @param StyleInterface $output The console output to use, if there is a need to ask
     *                              the user for a property value
     */
    public function __construct(Repository $config, $key, StyleInterface $output)
    {
        //parent::__construct();

        $this->config = $config;
        $this->key = $key;
        $this->output = $output;
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
        $value = $this->obtainValueFor($element);

        $this->saveValueFor($element, $value);
    }

    /**
     * Process the given template data property.
     *
     * Method is responsible for somehow process and obtain
     * the property's value, which thereafter can be used in
     * some context. If needed, this method will "ask" the
     * user for a value to be used.
     *
     * @see Property
     *
     * @param Property $property Some kind of a template data property
     *
     * @return void
     *
     * @throws CannotProcessPropertyException
     */
    public function processProperty(Property $property)
    {
        $this->processElement($property);
    }

    /**
     * Save the given property's value in this handler's
     * configuration
     *
     * @param Property $property
     * @param mixed $valueToBeSaved
     */
    protected function saveValueFor(Property $property, $valueToBeSaved)
    {
        $this->config->set($this->key, $valueToBeSaved);

        // Output what value is being used
        $this->outputStatus($valueToBeSaved, $property->getType(), $property->getId());
    }

    /**
     * Writes to the console what value is being used.
     *
     * Method will NOT display the value, if the type
     * corresponds to Type::HIDDEN
     *
     * @see \Aedart\Scaffold\Contracts\Templates\Data\Type::HIDDEN
     *
     * @param mixed $value
     * @param int $type
     * @param string $id
     */
    protected function outputStatus($value, $type, $id)
    {
        // The value to be displayed in the console
        $valueToDisplay = $value;

        // The format to use
        $format = 'info';

        // Check the type of the value
        if(is_null($value)){
            $valueToDisplay = 'null';
        }

        if(is_bool($value)){
            $valueToDisplay = ($value === true) ? 'true' : 'false';
        }

        // Check if type is hidden
        if($type == Type::HIDDEN){
            $valueToDisplay = '[censored]';
            $format = 'error';
        }

        // Finally, output the status
        $this->output->text("Using <{$format}>{$valueToDisplay}</{$format}> for <comment>{$id}</comment>");
    }

    protected function obtainValueFor(Property $property)
    {
        $type = $property->getType();
        $value = null;

        switch($type){
            case Type::VALUE:
                $value = $this->handleValueType($property);
                break;

            case Type::QUESTION:
                $value = $this->handleQuestionType($property);
                break;

            case Type::CHOICE:
                $value = $this->handleChoiceType($property);
                break;

            case Type::CONFIRM:
                $value = $this->handleConfirmType($property);
                break;

            case Type::HIDDEN:
                $value = $this->handleHiddenType($property);
                break;

            default:
                $value = $this->handleUnknownType($property);
                break;
        }

        // TODO: Implement "post process" of value

        // Finally, return the final value
        return $value;
    }

    /**
     * Process property of type 'value'
     *
     * Method just returns the value that is
     * specified in the given property.
     *
     * @param Property $property
     *
     * @return null|string
     */
    protected function handleValueType(Property $property)
    {
        return $property->getValue();
    }

    protected function handleQuestionType(Property $property)
    {
        // TODO: Implement validation
        return $this->output->ask($property->getQuestion(), $property->getValue(), null);
    }

    protected function handleChoiceType(Property $property)
    {
        return $this->output->choice($property->getQuestion(), $property->getChoices(), $property->getValue());
    }

    protected function handleConfirmType(Property $property)
    {
        return $this->output->confirm($property->getQuestion(), $property->getValue());
    }

    protected function handleHiddenType(Property $property)
    {
        // TODO: Implement validation

        // TODO: Hidden should always be confirmed - usage most likely for passwords...

        return $this->output->askHidden($property->getQuestion(), null);
    }

    /**
     * Process property of unknown type
     *
     * Method warns the user about the unknown type
     * and then uses a fallback; it attempts to just
     * handle the property as if it was of the type
     * 'value'
     *
     * @see handleValueType()
     *
     * @param Property $property
     *
     * @return null|string
     */
    protected function handleUnknownType(Property $property)
    {
        $message = "Unknown property type '{$property->getType()}' on '{$property->getId()}'." . PHP_EOL;
        $message .= "Using fallback of type 'VALUE' to process property.";

        $this->output->warning($message);

        return $this->handleValueType($property);
    }
}