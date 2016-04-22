<?php namespace Aedart\Scaffold\Console;

use Aedart\Config\Loader\Traits\ConfigLoaderTrait;
use Aedart\Scaffold\Tasks\CopyFiles;
use Aedart\Scaffold\Tasks\CreateDirectories;
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
            ->setHelp(<<<EOT
Base on a scaffold configuration file, this command will do the following;

1) Create specified directories
2) Copy a set of source files into specified destinations
3) Aks you for eventual input, based on 'templateData' and 'templates' sections of the given configuration
4) Generate files based on specified templates and eventual given input

Usage:

<info>php scaffold build resources/scaffold_aedart_composer_example.php</info>

The above command will load and read the specified scaffold configuration file and process it.
All directories and files will be created, copied or generated into the <debug>current working directory</debug>.
You can, however, also specify a desired output directory:

<info>php scaffold build resources/scaffold_aedart_composer_example.php --output /home/vagrant/Aedart/MyProject/</info>
EOT
            );
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
        // "scaffold_aedart_templates_composer" corresponds to the
        // filename of the given scaffold. We have no way of knowing
        // or guessing that given name.
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

        // Output title and what configuration file is being used
        $this->output->title(sprintf('Building %s', $config->get('name')));
        $this->output->text(sprintf('Using: %s', $this->input->getArgument('config')));

        // Execute builder's tasks
        $i = 1;
        $total = count($this->tasks);
        foreach($this->tasks as $task){
            // Output task info
            $this->output->section($this->normaliseTaskName($task));
            $this->output->text("Task ({$i}/{$total})");
            $this->output->newLine();

            // Execute the task
            (new $task)->execute($this->input, $this->output, $config);
            $i++;
        }

        // Output done msg
        $this->output->newLine();
        $this->output->success(sprintf('%s completed', $config->get('name')));
    }

    /**
     * Normalise the given task class path
     *
     * @param string $taskPath
     *
     * @return string
     */
    protected function normaliseTaskName($taskPath)
    {
        $a = Str::snake($taskPath);
        $b = explode('\\', $a);
        $c = str_replace('_', ' ', array_pop($b));

        return Str::ucfirst(trim($c));
    }
}