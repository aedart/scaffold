<?php

namespace Aedart\Scaffold\Console\Style;

use Aedart\Scaffold\Contracts\Console\Style\Factory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Extended Style Output Factory
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console\Style
 */
class ExtendedStyleFactory implements Factory
{
    /**
     * Input values
     *
     * @var resource|null
     */
    protected $inputValues;

    /**
     * ExtendedStyleFactory constructor.
     *
     * @param resource|null $inputValues [optional]
     */
    public function __construct($inputValues = null)
    {
        $this->inputValues = $inputValues;
    }

    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output)
    {
        $style = new ExtendedStyle($input, $output);

        // Set input steam (input values) if any are given
        if(isset($this->inputValues)){
            $style->getQuestionHelper()->setInputStream(
                $this->inputValues
            );
        }

        return $style;
    }
}