<?php

namespace Aedart\Scaffold\Console\Style;

use Aedart\Scaffold\Contracts\Console\Style\Factory as OutputStyleFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @deprecated Since 2.0 Use \Aedart\Scaffold\Console\Style\ExtendedStyleFactory instead
 *
 * Output Style Factory
 *
 * @see \Aedart\Scaffold\Contracts\Console\Style\Factory
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console\Style
 */
class Factory implements OutputStyleFactory
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output)
    {
        return new SymfonyStyle($input, $output);
    }
}