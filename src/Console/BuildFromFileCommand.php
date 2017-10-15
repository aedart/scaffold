<?php

namespace Aedart\Scaffold\Console;

use Aedart\Laravel\Helpers\Traits\Filesystem\FileTrait;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build From File Command
 *
 * Accepts a file as arguments, in which one or several scaffold locations
 * and eventual inputs are given.
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class BuildFromFileCommand extends BaseCommand
{
    use FileTrait;

    /**
     * The build command to use
     */
    protected CONST BUILD_COMMAND = 'build';

    /**
     * The actual build command
     *
     * @var Command|null
     */
    protected $buildCommand = null;

    /**
     * @inheritdoc
     */
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

        // Output Title
        $this->output->title('Building From File');
        $this->output->comment(sprintf('File %s', $path));

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

        // Find and set the build command
        $this->buildCommand = $this->getApplication()->find(self::BUILD_COMMAND);

        // Build the scaffold(s)
        $this->buildScaffolds($data);

        // Output complete
        $this->output->newLine();
        $this->output->success('Completed');
    }

    /**
     * Build a series of scaffolds
     *
     * @see buildScaffold
     *
     * @param array $scaffolds
     */
    protected function buildScaffolds(array $scaffolds = [])
    {
        // Create new progress bar
        $progress = $this->output->createProgressBar(count($scaffolds));

        // Build scaffolds
        foreach ($scaffolds as $scaffold){

            // Build...
            $this->buildScaffold($scaffold);

            // Update progress
            $progress->advance();
        }

        // Finally, complete the progress
        $progress->finish();
    }

    /**
     * Build a scaffold
     *
     * @param array $scaffold Must contain "location" (path) to the scaffold to build
     */
    protected function buildScaffold(array $scaffold) : void
    {
        // Assert scaffold location is given
        if( ! isset($scaffold['location'])){
            throw new InvalidArgumentException(sprintf('Missing "location" in %s', var_export($scaffold, true)));
        }

        // Properties
        $location = $scaffold['location'];
        $input = $scaffold['input'] ?? [];
        $output = $scaffold['output'] ?? getcwd();

        // And also... check if the scaffold does exists
        // We do this here (too), to avoid having to start nested command.
        if( ! file_exists($location)){
            throw new InvalidArgumentException(sprintf('Scaffold "%s" does not exist', $location));
        }

        // Prepare the command arguments
        $arguments = [
            'command'   => self::BUILD_COMMAND,
            'config'    => $location,
            '--output'  => $output,
            '--input'   => $input,
        ];

        // Determine the output (verbosity)
        // Useful for debugging and tests
        $output = new NullOutput();
        if($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERY_VERBOSE){
            $output = $this->output;
        }

        // Build the scaffold
        $this->buildCommand->run(
            new ArrayInput($arguments),
            $output
        );
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

This command executes the <info>build</info> command, for each provided
scaffold-location. It also passes on eventual input (answers to questions)

File Example:

<comment>
<?php
return [
    [
        // The location to the scaffold file
        'location'  => __DIR__ . '/inputFromOption.scaffold.php',
        
        // Input arguments to be passed to the scaffold
        // Acts as "answers" to questions...
        'input'     => [
            'AEDART/a'
        ]
    ],
    [
        'location'  => __DIR__ . '/inputFromOption.scaffold.php',
        'input'     => [
            'Acme/b'
        ]
    ],
    [
        'location'  => __DIR__ . '/inputFromOption.scaffold.php',
        'input'     => [
            'Punk/c'
        ]
    ],
];
</comment>
EOT;
    }
}