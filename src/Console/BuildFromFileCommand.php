<?php

namespace Aedart\Scaffold\Console;


use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;

class BuildFromFileCommand extends BaseCommand
{
    use FileTrait;

    protected function configure()
    {
        $this
            ->setName('build:file')
            ->setDescription('Build one or more scaffolds, stated in a php file. File must return an array with build command arguments!')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the source file that contains build command arguments')
            ->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Obtain file path
        $path = $this->input->getArgument('file');

        // Fail if file does not exist
        if( ! $this->getFile()->exists($path)){
            throw new InvalidArgumentException(sprintf('File %s does not exist or unable to read it.', $path));
        }

        // Require the php file... Note we just allow php natively to
        // fail, if this does not work...
        $data = require $path;

        // Check if data is an array
        if( ! is_array($data)){
            throw new InvalidArgumentException(sprintf('File %s must return a valid array', $path));
        }

        // Convert to nested list, in case that a single scaffold
        // is ordered to be built.
        $data = Arr::isAssoc($data) ? [$data] : $data;

        // TODO: Loop through data... execute build command with all available args / options.
        // TODO: Fail if required args are not given
    }

    /**
     * Formats and returns this commands help text
     *
     * @return string
     */
    protected function formatHelp()
    {
        return <<<EOT
Build one or more scaffolds, which are stated in a source php file.

Usage:

<info>php scaffold build:file my-scaffolds-to-build.php</info>

EOT;
    }
}