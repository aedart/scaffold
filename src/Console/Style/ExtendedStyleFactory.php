<?php

namespace Aedart\Scaffold\Console\Style;

use Aedart\Scaffold\Contracts\Console\Style\Factory;
use Aedart\Scaffold\Testing\Console\Style\ExtendedStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExtendedStyleFactory implements Factory
{
    /**
     * Input values
     *
     * @var resource
     */
    protected $inputValues;

    /**
     * ExtendedStyleFactory constructor.
     *
     * @param resource $inputValues
     */
    public function __construct($inputValues)
    {
        $this->inputValues = $inputValues;
    }

    /**
     * Make a new Symfony Style instance
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return SymfonyStyle
     */
    public function make(InputInterface $input, OutputInterface $output)
    {
        $style = new ExtendedStyle($input, $output);

        $style->getQuestionHelper()->setInputStream(
            $this->inputValues
        );

        return $style;
    }
}