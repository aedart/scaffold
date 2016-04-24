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
        // TODO: Do NOT display value, if type is hidden!
        // TODO: Display 'true / false' for boolean values
        $this->output->text("Using <info>{$valueToBeSaved}</info> for <comment>{$property->getId()}</comment>");
    }

    protected function obtainValueFor(Property $property)
    {
        $type = $property->getType();
        $value = null;

        switch($type){
            case Type::VALUE:
                $value = $property->getValue();
                break;

            case Type::QUESTION:
                // TODO: Implement validation
                $value = $this->output->ask($property->getQuestion(), $property->getValue(), null);
                break;

            case Type::CHOICE:
                $value = $this->output->choice($property->getQuestion(), $property->getChoices(), $property->getValue());
                break;

            case Type::CONFIRM:
                $value = $this->output->confirm($property->getQuestion(), $property->getValue());
                break;

            case Type::HIDDEN:
                // TODO: Implement validation

                // TODO: Hidden should always be confirmed - usage most likely for passwords...

                $value = $this->output->askHidden($property->getQuestion(), null);
                break;

            default:
                // TODO: Fail here...
                $this->output->warning("throw exception for '{$property->getId()}', type {$property->getType()} is unsupported");
                break;
        }

        // TODO: Implement "post process" of value

        // Finally, return the final value
        return $value;
    }
}