<?php namespace Aedart\Scaffold\Console;

use Aedart\Config\Loader\Traits\ConfigLoaderTrait;
use Aedart\Scaffold\Tasks\CopyFiles;
use Aedart\Scaffold\Tasks\CreateDirectories;
use Illuminate\Config\Repository;
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
    use ConfigLoaderTrait;

    /**
     * List of console tasks (paths) that this command
     * must perform / execute.
     *
     * NB: The list is ordered !
     *
     * @var string[]
     */
    protected $tasks = [
        CreateDirectories::class,
        CopyFiles::class,
    ];

    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Creates folders, copies and generate files into the given output path')
            ->addArgument('config', InputArgument::REQUIRED, 'Path to the scaffold configuration file')
            ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'Path where to build the scaffold folders and files', getcwd())
            ->setHelp($this->formatHelp());
    }

    /**
     * Execute the command
     *
     * @return int|null
     */
    public function runCommand()
    {
        // Load and resolve the configuration
        $config = $this->loadAndResolveConfiguration($this->input->getArgument('config'), $this->input->getOption('output'));

        // Execute builder's tasks
        $i = 1;
        $total = count($this->tasks);
        foreach($this->tasks as $task){
            // Create new task instance
            /** @var \Aedart\Scaffold\Contracts\Tasks\ConsoleTask $taskToExecute */
            $taskToExecute = (new $task);

            // Output task info
            $this->output->section($taskToExecute->getName());
            $this->output->text("Task ({$i}/{$total})");
            $this->output->newLine();

            // Execute the task
            $taskToExecute->execute($this->input, $this->output, $config);
            $i++;
        }

        // Output done msg
        $this->output->newLine();
        $this->output->success(sprintf('%s completed', $config->get('name')));
    }

    /**
     * Loads and resolves the scaffold configuration
     *
     * @param string $pathToConfig
     * @param string $outputPath
     *
     * @return Repository|\Illuminate\Contracts\Config\Repository
     */
    protected function loadAndResolveConfiguration($pathToConfig, $outputPath)
    {
        // Load configuration
        $config = $this->getConfigLoader()->parse($pathToConfig);

        // Get the filename (without file extension) so that we know what
        // part of the configuration to fetch. The filename acts as the
        // configuration's entry key.
        $filename = strtolower(pathinfo($pathToConfig, PATHINFO_FILENAME));

        // Fetch the content of the configuration and pass it
        // into a new configuration, which is the one that we are
        // going to pass on to each task
        $config = new Repository($config->get($filename));

        // Resolve the output path and added it to the configuration
        if(substr($outputPath, -1) != DIRECTORY_SEPARATOR){
            $outputPath = $outputPath . DIRECTORY_SEPARATOR;
        }
        $config->set('outputPath', $outputPath);

        // Output title and what configuration file is being used
        $this->output->title(sprintf('Building %s', $config->get('name')));
        $this->output->text(sprintf('Using: %s', $pathToConfig));

        // Finally, return the configuration
        return $config;
    }

    /**
     * Formats and returns this commands help text
     *
     * @return string
     */
    protected function formatHelp()
    {
        $taskDescriptions = $this->formatTasksDescriptions();

        return <<<EOT
Base on a scaffold configuration file, this command will do the following;

{$taskDescriptions}
Usage:

<info>php scaffold build resources/scaffold_aedart_composer_example.php</info>

The above command will load and read the specified scaffold configuration file and process it.
All directories and files will be created, copied or generated into the <debug>current working directory</debug>.
You can, however, also specify a desired output directory:

<info>php scaffold build resources/scaffold_aedart_composer_example.php --output /home/vagrant/Aedart/MyProject/</info>
EOT;
    }

    /**
     * Returns each added task's description
     *
     * @return string
     */
    protected function formatTasksDescriptions()
    {
        $output = "";

        $i = 1;
        foreach($this->tasks as $task){
            $desc = (new $task)->getDescription();

            $output .= "<info>{$i}</info> {$desc}" . PHP_EOL;

            $i++;
        }

        return $output;
    }
}