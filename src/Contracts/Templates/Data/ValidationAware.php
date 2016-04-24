<?php namespace Aedart\Scaffold\Contracts\Templates\Data;

/**
 * Validation Aware
 *
 * Component is aware of a callable validation method,
 * which can be applied on a given property value.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
interface ValidationAware
{
    /**
     * Set the given validation
     *
     * @param callable $method Callable validation method
     *
     * @return void
     */
    public function setValidation(callable $method);

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
    public function getValidation();

    /**
     * Get a default validation value, if any is available
     *
     * @return callable|null A default validation value or Null if no default value is available
     */
    public function getDefaultValidation();

    /**
     * Check if validation has been set
     *
     * @return bool True if validation has been set, false if not
     */
    public function hasValidation();

    /**
     * Check if a default validation is available or not
     *
     * @return bool True of a default validation is available, false if not
     */
    public function hasDefaultValidation();
}