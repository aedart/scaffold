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

        $value = $this->applyPostProcessOn($value, $element);

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

    /**
     * Obtain the value from the given property
     *
     * Depending upon the property's type, a different
     * action is performed, in order to retrieve the
     * value.
     *
     * E.g. If the property has a type of 'question',
     * then the user is asked that question and the
     * his/hers answer is then returned by this method.
     *
     * @see \Aedart\Scaffold\Contracts\Templates\Data\Type
     * @see \Aedart\Scaffold\Contracts\Templates\Data\Property
     *
     * @param Property $property
     *
     * @return mixed The property's processed value
     */
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

        // Finally, return the final value
        return $value;
    }

    /**
     * Applies the given property's post-process
     * callable method on the given value.
     *
     * If property has no post-process method, then
     * the value is returned.
     *
     * @param mixed $value
     * @param Property $property
     *
     * @return mixed
     */
    protected function applyPostProcessOn($value, Property $property)
    {
        if(!$property->hasPostProcess() && !$property->hasDefaultPostProcess()){
            return $value;
        }

        $this->output->text("Applying post-process on <comment>{$property->getId()}</comment>");

        return call_user_func($property->getPostProcess(), $value);
    }

    /**
     * Process property of type 'value'
     *
     * Method just returns the value that is
     * specified in the given property.
     *
     * @param Property $property
     *
     * @return null|string Property's value
     */
    protected function handleValueType(Property $property)
    {
        return $property->getValue();
    }

    /**
     * Process property of the type 'question'
     *
     * Method will ask the user a question and return
     * the user's answer.
     *
     * @param Property $property
     *
     * @return string Answer to the question
     */
    protected function handleQuestionType(Property $property)
    {
        return $this->output->ask($property->getQuestion(), $property->getValue(), $property->getValidation());
    }

    /**
     * Process property of type 'choice'
     *
     * Method will ask user to select one or the given
     * choices that are available in the property.
     *
     * @see \Aedart\Scaffold\Contracts\Templates\Data\Property::getChoices()
     *
     * @param Property $property
     *
     * @return string Selected choice
     */
    protected function handleChoiceType(Property $property)
    {
        return $this->output->choice($property->getQuestion(), $property->getChoices(), $property->getValue());
    }

    /**
     * Process property of type 'confirm'
     *
     * Method will ask the user to confirm something.
     *
     * @param Property $property
     *
     * @return bool
     */
    protected function handleConfirmType(Property $property)
    {
        return $this->output->confirm($property->getQuestion(), $property->getValue());
    }

    /**
     * Process property of the type 'hidden'
     *
     * Method will ask the user for a value and thereafter
     * ask the user to confirm that value. Just like when
     * you are asked for creating a password, in most
     * systems.
     *
     * If the the hidden value is not conformed correctly,
     * the method will try again (ask the user again), until
     * there are no more attempts left.
     *
     * @param Property $property
     * @param int $maxAttempts [optional]
     * @param int $attemptNumber [optional]
     *
     * @return string The hidden value
     *
     * @throws CannotProcessPropertyException If maximum attempts has been reached
     */
    protected function handleHiddenType(Property $property, $maxAttempts = 3, $attemptNumber = 1)
    {
        $value = $this->output->askHidden($property->getQuestion(), $property->getValidation());

        // Confirm the value that has been given
        // NOTE: We cannot use the builtin validation for this confirmation,
        // because the question helper is repeatedly going to ask for
        // the same value. However, the user might NOT know where the mistake
        // was made; first time the hidden value was typed or the second time?
        // Therefore, we must manually validate the two values
        $confirmedValue = $this->output->askHidden("Repeat ({$property->getQuestion()})!", null);

        // Check if values match
        if($confirmedValue == $value){
            return $value;
        }

        // This means that the values do not match.
        // Thus, we must check if we have any attempts left...
        if($attemptNumber >= $maxAttempts){
            throw new CannotProcessPropertyException("Incorrect values have been given for '{$property->getId()}', maximum attempts reached!");
        }

        // If reached here, it means that the two values do not match,
        // and we still have one or more attempts to resolve the issue.
        // We do so by simply asking the user again.
        // Warn the user
        $this->output->warning("The two values do not match. Please try again. Attempt no.: ({$attemptNumber}/{$maxAttempts})");

        // Increase attempt and
        $attemptNumber++;

        // Try again...
        return $this->handleHiddenType($property, $maxAttempts, $attemptNumber);
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
     * @return null|string Property's value
     */
    protected function handleUnknownType(Property $property)
    {
        $message = "Unknown property type '{$property->getType()}' on '{$property->getId()}'." . PHP_EOL;
        $message .= "Using fallback of type 'VALUE' to process property.";

        $this->output->warning($message);

        return $this->handleValueType($property);
    }
}