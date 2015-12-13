<?php namespace Aedart\Scaffold\Handlers;

use Aedart\Model\Traits\Arrays\DataTrait;
use Aedart\Scaffold\Contracts\Handlers\TemplateHandler as TemplateHandlerInterface;
use Aedart\Scaffold\Exceptions\CouldNotCreateFileException;
use Aedart\Scaffold\Exceptions\FileAlreadyExistsException;
use Aedart\Scaffold\Traits\TemplateEngineTrait;

/**
 * <h1>Template-Handler</h1>
 *
 * @see \Aedart\Scaffold\Contracts\Handlers\TemplateHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Handlers
 */
class TemplateHandler extends BaseFileHandler implements TemplateHandlerInterface {

    use DataTrait, TemplateEngineTrait;

    public function processElement($element) {
        // Obtain the template engine instance
        $engine = $this->getTemplateEngine();

        // Set the engine's base path
        $engine->setBasePath($this->getBasePath());

        // Specify the template to be rendered
        $engine->setTemplate($element);

        // Set the data to be parsed
        $engine->setData($this->getData());

        // Perform 'setup' on engine
        $engine->setup();

        // Render the template
        $content = $engine->render();

        // Compute the final output path
        $path = $this->computeOutputPath($element, $this->getFilename());

        // Finally, write the content to the file
        $this->writeFile($path, $content);
    }

    /**
     * Returns the output-path the file to be created, relative
     * to where the template is located inside the base-path (the scaffold directory)
     *
     * <b>Example</b>;
     * <pre>
     *      // Filename
     *      MyComponent.php
     *
     *      // Template inside the scaffold directory
     *      + my_scaffold
     *          + stuff
     *              myComponent.php.tpl
     *
     *      // Output of file will be
     *      + my_output
     *          + stuff
     *              MyComponent.php
     * </pre>
     *
     * @param string $template Path and name of the template
     * @param string $filename Path and name of the output file
     *
     * @return string
     */
    public function computeOutputPath($template, $filename) {
        $parentDirectory = dirname($template);

        $finalOutputPath = $this->getOutputPath() . str_replace($this->getBasePath(), '', $parentDirectory) . '/' . $filename;

        return $finalOutputPath;
    }

    /**
     * Create (write) to the given file
     *
     * @param string $filePath Output file path
     * @param string $content File contents
     *
     * @throws FileAlreadyExistsException In case file already exists
     * @throws CouldNotCreateFileException If unable to create the file
     */
    public function writeFile($filePath, $content) {
        // Get the filesystem
        $fs = $this->getFile();

        // Check if file already exists
        if($fs->exists($filePath)){
            throw new FileAlreadyExistsException(sprintf('File "%s" already exists, will NOT overwrite it', $filePath));
        }

        // Write the file
        $result = $fs->put($filePath, $content);

        // Lastly, check if file was actually created
        if($result === false){
            throw new CouldNotCreateFileException(sprintf('File "%s" was not created. Please check read / write permissions of the given output destination', $filePath));
        }
    }
}