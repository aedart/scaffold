<?php namespace Aedart\Scaffold\Console;

use Aedart\Scaffold\Traits\ConfigLoader;
use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Build Command
 *
 * Builds folders and files, based on the given configuration
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console
 */
class BuildCommand extends BaseCommand
{
    use ConfigLoader;

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Creates folders, copies and generate files into the given output path')
            ->addArgument('config', InputArgument::REQUIRED, 'Path to the scaffold configuration file')
            ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Path where to build the scaffold folders and files', getcwd());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Load configuration
        $config = $this->getConfigLoader()->parse($this->input->getArgument('config'));

        // The configuration is loader, yet we will have some issues
        // attempting to read anything from it, because all of the
        // "scaffold's" configuration is stored inside a user defined
        // entry, e.g. "scaffold_aedart_composer.folders", where the
        // "scaffold_aedart_composer" corresponds to the filename of
        // the given scaffold. We have no way of knowing or guessing
        // that given name.
        //
        // Therefore, we need to parse all loaded entries into a new
        // configuration instance, so that we can access it via a
        // predefined accessor.

        $rawEntries = $config->all();
        $newEntries = array_shift($rawEntries);

        // Now, we simple create a new Repository in which we can
        // access the configuration entries directly, e.g.
        // "name", "folders", "files", ... etc
        $config = new Repository($newEntries);

        // Resolve the output path and added it to the configuration
        $outputPath = $this->input->getOption('output');
        if(substr($outputPath, -1) != DIRECTORY_SEPARATOR){
            $outputPath = $outputPath . DIRECTORY_SEPARATOR;
        }
        $config->set('outputPath', $outputPath);

        // Output title
        $this->output->title(sprintf('Building %s', $config->get('name')));

        // Define all of this builder's tasks
        $tasks = [
            \Aedart\Scaffold\Tasks\CreateDirectories::class,
        ];

        // Execute builder's tasks
        foreach($tasks as $task){
            $this->output->section($this->normaliseTaskName($task));

            (new $task)->execute($this->input, $this->output, $config);
        }
    }

    /**
     * Normalise the given task class path
     *
     * @param string $taskPath
     *
     * @return string
     */
    protected function normaliseTaskName($taskPath){
        $a = Str::snake($taskPath);
        $b = explode('\\', $a);
        $c = str_replace('_', ' ', array_pop($b));

        return Str::ucfirst(trim($c));
    }
}