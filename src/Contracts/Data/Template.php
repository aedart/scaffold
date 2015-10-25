<?php namespace Aedart\Scaffold\Contracts\Data;

use Aedart\DTO\Contracts\DataTransferObject;
use Aedart\Model\Contracts\Strings\IdAware;
use Aedart\Scaffold\Contracts\HandlerAware;
use Aedart\Scaffold\Contracts\Data\AskableProperty as AskablePropertyInterface;

/**
 * <h1>Template</h1>
 *
 * In this context, a template contains information about a the name of the
 * template-file (id), what handler it must use, as well as what output
 * filename it should have, if it is being rendered.
 *
 * <br />
 *
 * The output filename is set and retrieved as an Askable-Property, meaning
 * that it is possible for the higher-level application to ask for an output
 * filename, if a default is not available.
 *
 * @see \Aedart\Scaffold\Contracts\Data\AskableProperty
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Data
 */
interface Template extends DataTransferObject,
    IdAware,
    HandlerAware
{

    /**
     * Set the filename - the rendered output filename of
     * the template that a given file instance must use
     *
     * @param \Aedart\Scaffold\Contracts\Data\AskableProperty|null $property [optional]
     *
     * @return void
     */
    public function setFilename(AskablePropertyInterface $property = null);

    /**
     * Get the filename - the rendered output filename of
     * the template that a given file instance must use
     *
     * @return \Aedart\Scaffold\Contracts\Data\AskableProperty|null
     */
    public function getFilename();
}