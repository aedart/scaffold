<?php namespace Aedart\Scaffold\Traits;

/**
 * Validation
 *
 * @see \Aedart\Scaffold\Contracts\Templates\Data\ValidationAware
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait Validation
{
    /**
     * Callable validation method
     *
     * @var callable|null
     */
    protected $validation = null;

    /**
     * Set the given validation
     *
     * @param callable $method Callable validation method
     *
     * @return void
     */
    public function setValidation(callable $method)
    {
        $this->validation = $method;
    }

    /**
     * Get the given validation
     *
     * If no validation has been set, this method will
     * set and return a default validation, if any such
     * value is available
     *
     * @see getDefaultValidation()
     *
     * @return callable|null validation or null if none validation has been set
     */
    public function getValidation()
    {
        if (!$this->hasValidation() && $this->hasDefaultValidation()) {
            $this->setValidation($this->getDefaultValidation());
        }
        return $this->validation;
    }

    /**
     * Get a default validation value, if any is available
     *
     * @return callable|null A default validation value or Null if no default value is available
     */
    public function getDefaultValidation()
    {
        return null;
    }

    /**
     * Check if validation has been set
     *
     * @return bool True if validation has been set, false if not
     */
    public function hasValidation()
    {
        return !is_null($this->validation);
    }

    /**
     * Check if a default validation is available or not
     *
     * @return bool True of a default validation is available, false if not
     */
    public function hasDefaultValidation()
    {
        return !is_null($this->getDefaultValidation());
    }
}