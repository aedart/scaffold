<?php

use Aedart\Config\Loader\Factories\DefaultParserFactory;
use Aedart\Config\Loader\Loaders\ConfigLoader;
use Aedart\Scaffold\Handlers\FileHandler;
use Codeception\Configuration;
use Codeception\Util\Debug;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class FileHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\FileHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class FileHandlerTest extends HandlerTestCase{

    /**
     * @var Repository
     */
    protected $config = null;

    /**
     * @var string
     */
    protected $targetDir = null;

    /**
     * TODO: This can also be split into smaller abstractions / helpers inside the test-case
     */
    public function _before() {
        $dataDir = Configuration::dataDir();

        $handlersDir = $dataDir . 'handlers/';

        $this->targetDir = $handlersDir . 'files/fileHandler/';

        $loader = new ConfigLoader($this->targetDir);

        $loader->setConfig(new Repository());
        $loader->setFile(new Filesystem());
        $loader->setParserFactory(new DefaultParserFactory());

        $loader->load();

        $this->config = $loader->getConfig();
    }

    /**
     * TODO: can be abstracted ...
     *
     * @return FileHandler
     */
    public function makeHandler() {
        return new FileHandler();
    }

    /************************************************************************
     * Actual tests
     ***********************************************************************/

    /**
     * TODO: This is more of a debugging thing..
     *
     * @test
     */
    public function showScaffoldConfiguration() {
        Debug::debug($this->config);
    }

    /**
     * TODO: Not too sure that this is actually needed to be tested
     *
     * @test
     */
    public function canSpecifyConfiguration(){
        $handler = $this->makeHandler();
        $handler->setConfig($this->config);

        $this->assertSame($this->config, $handler->getConfig());
    }

    /**
     * @test
     */
    public function hasDefaultBasePathSet() {
        $handler = $this->makeHandler();
        $handler->setConfig($this->config);

        Debug::debug($handler->getBasePath());

        $this->assertNotEmpty($handler->getBasePath(), 'Base path is empty');
        $this->assertSame($this->config->get('scaffold.basePath'), $handler->getBasePath());
    }

    /**
     * @test
     */
    public function hasDefaultDataSet() {
        $handler = $this->makeHandler();
        $handler->setConfig($this->config);

        Debug::debug($handler->getData());

        $this->assertNotEmpty($handler->getData(), 'Data is empty');
        $this->assertSame($this->config->get('scaffold.data'), $handler->getData());
    }

    /**
     * @test
     */
    public function canProcessData() {
        $handler = $this->makeHandler();
        $handler->setConfig($this->config);

        $processedData = $handler->processData($handler->getData());

        Debug::debug($processedData);

        foreach($processedData as $key => $value){
            $this->assertArrayNotHasKey('default', (array) $key, 'Entry key has a "default"!');
        }
    }

    /**
     * @test
     */
    public function canHandleTemplate() {
        $handler = $this->makeHandler();
        $handler->setConfig($this->config);

        $outputDir = Configuration::outputDir();

        $outputHandlers = $outputDir . 'handlers/';

        $outputPath = $outputHandlers . 'files/fileHandler/';

        $handler->setOutputPath($outputPath);

        $configFiles = $this->config->get('scaffold.files');

        $filename = null;
        foreach($configFiles as $template => $config){
            $filename = $config['filename'];
        }

        $handler->setFilename($filename);

        //$handler->handle($this->targetDir . 'templates/composer.json.twig');
        $handler->handle('composer.json.twig');
    }
}