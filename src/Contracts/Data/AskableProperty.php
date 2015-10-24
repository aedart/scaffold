<?php namespace Aedart\Scaffold\Contracts\Data;

use Aedart\DTO\Contracts\DataTransferObject;
use Aedart\Model\Contracts\Booleans\Askable;
use Aedart\Model\Contracts\Strings\DescriptionAware;
use Aedart\Model\Contracts\Strings\IdAware;

/**
 * <h1>Askable Property</h1>
 *
 * An askable-property is an object that contains a state, which
 * indicates if a value should be asked for or not. Furthermore, it
 * can also contains the actual value, along with an id and a description
 * of what this value is to be used for
 *
 * <br />
 *
 * The intended use for this data object, is to hold eventually default
 * defined values, which can be used inside some given template file or
 * to ask for a value, should this be required, e.g. via the console.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Data
 */
interface AskableProperty extends DataTransferObject,
    IdAware,
    Askable,
    DescriptionAware
{

    /**
     * Set the default value to be used
     *
     * @param mixed|null $value [optional] The default value
     */
    public function setDefault($value = null);

    /**
     * Get the default value, if any is available
     *
     * @return mixed|null
     */
    public function getDefault();

    /**
     * Check if any default value is available
     *
     * @return bool True if any default value is available, false if not
     */
    public function hasDefault();
}