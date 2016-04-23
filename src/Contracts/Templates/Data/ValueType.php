<?php namespace Aedart\Scaffold\Contracts\Templates\Data;

/**
 * Template Value Type
 *
 * Various supported template "value" types. Depending upon
 * what type is specified, different action must be performed
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Templates
 */
interface ValueType
{

    /**
     * Default - the user is not prompted
     * for any value.
     */
    const VALUE = 10;

    /**
     * Should prompt the user for a value,
     * via a question.
     */
    const QUESTION = 20;

    /**
     * Should prompt the user with a
     * multiple choice question
     */
    const CHOICE = 30;

    /**
     * Should prompt the user for a value,
     * via a question, where the input is
     * hidden
     */
    const HIDDEN = 40;

    /**
     * Ask user for confirmation
     */
    const CONFIRM = 50;
}