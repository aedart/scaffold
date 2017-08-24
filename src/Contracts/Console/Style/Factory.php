<?php

namespace Aedart\Scaffold\Contracts\Console\Style;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Output Style Factory
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Contracts\Console\Style
 */
interface Factory
{
    /**
     * Make a new Symfony Style instance
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return SymfonyStyle
     */
    public function make(InputInterface $input, OutputInterface $output);
}