<?php

use Aedart\Scaffold\Contracts\Collections\TemplateProperties;
use Aedart\Scaffold\Contracts\Handlers\DirectoriesHandler;
use Aedart\Scaffold\Contracts\Templates\Data\Type;
use Aedart\Scaffold\Handlers\TwigTemplateHandler;
use Illuminate\Contracts\Logging\Log;
use Mockery as m;

/**
 * Class TwigTemplateHandlerTest
 *
 * @group handlers
 * @group templateHandler
 *
 * @coversDefaultClass Aedart\Scaffold\Handlers\TwigTemplateHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TwigTemplateHandlerTest extends BaseUnitTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function dataPath()
    {
        return parent::dataPath() . 'handlers/templates/';
    }

    public function outputPath()
    {
        return parent::outputPath() . 'handlers/templates/';
    }

    /**
     * Returns a new Template handler instance
     *
     * @param Log|null $log [optional]
     *
     * @param TemplateProperties $collection [optional]
     *
     * @return TwigTemplateHandler
     */
    public function makeTemplateHandler(Log $log = null, TemplateProperties $collection = null)
    {
        $directoryHandler = $this->makeDirectoryHandlerMock();

        $handler = new TwigTemplateHandler($directoryHandler, $collection);

        $handler->setBasePath($this->dataPath());
        $handler->setOutputPath($this->outputPath());
        $handler->setFile($this->getFilesystem());

        if(!is_null($log)){
            $handler->setLog($log);
        }

        return $handler;
    }

    /**
     * Returns a directory handler mock
     *
     * @return m\MockInterface|DirectoriesHandler
     */
    public function makeDirectoryHandlerMock()
    {
        $handler = m::mock(DirectoriesHandler::class);

        $handler->shouldReceive('setFile')
            ->withAnyArgs();

        $handler->shouldReceive('setLog')
            ->withAnyArgs();

        $handler->shouldReceive('setBasePath')
            ->withAnyArgs();

        $handler->shouldReceive('setOutputPath')
            ->withAnyArgs();

        $handler->shouldReceive('processDirectories')
            ->withAnyArgs();

        return $handler;
    }

    /**
     * Returns a template mock with the given properties
     *
     * @param string $id
     * @param string $source
     * @param string $destination
     *
     * @return \Aedart\Scaffold\Contracts\Templates\Template|\Mockery\MockInterface
     */
    public function makeTemplateWith($id, $source, $destination)
    {
        // Make a destination property
        $destinationProp = $this->makePropertyMock(Type::VALUE);
        $destinationProp->shouldReceive('getValue')
            ->andReturn($destination);

        // Make a template
        $template = $this->makeTemplateMock($destinationProp, $id);
        $template->shouldReceive('getSource')
            ->andReturn($source);

        return $template;
    }

    /**
     * Returns a mock of a template properties collection
     *
     * @param array $data [optional]
     *
     * @return m\MockInterface|TemplateProperties
     */
    public function makeTemplatePropertiesMock(array $data = [])
    {
        $collection = m::mock(TemplateProperties::class);
        $collection->shouldReceive('all')
            ->andReturn($data);

        return $collection;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::processTemplate
     * @covers ::processElement
     *
     * @covers ::setupEngine
     * @covers ::getEngineOptions
     * @covers ::generateFileFrom
     * @covers ::prepareTemplateData
     * @covers ::generateFile
     * @covers ::renderTemplate
     * @covers ::writeFile
     * @covers ::prepareDestinationPath
     * @covers ::configureDirectoryHandler
     */
    public function canGenerateFileFromTemplate()
    {
        // Make a template
        $templateId = 'myFile';
        $source = 'myFile.txt.twig';
        $destination= 'myFile.txt';
        $template = $this->makeTemplateWith($templateId, $source, $destination);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeTemplateHandler($log);

        // Process template
        $handler->processTemplate($template);

        // Assert
        $data = [
            $this->dataPath() . $source,
            $this->outputPath() . $destination
        ];

        $this->assertPathsOrFilesExist([$destination], 'Destination file was not created by handler');
        $this->assertFileContainsData($destination, $data);
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::processTemplate
     * @covers ::processElement
     *
     * @covers ::setupEngine
     * @covers ::getEngineOptions
     * @covers ::generateFileFrom
     * @covers ::prepareTemplateData
     * @covers ::parseTemplateDataToArray
     * @covers ::generateFile
     * @covers ::renderTemplate
     */
    public function canGenerateFileWithCorrectData()
    {
        // Make a template
        $templateId = 'myOtherFile';
        $source = 'myOtherFile.txt.twig';
        $destination= 'myOtherFile.txt';
        $template = $this->makeTemplateWith($templateId, $source, $destination);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        $fooValue = $this->faker->unique()->uuid;
        $foo = $this->makePropertyMock();
        $foo->shouldReceive('getValue')
            ->andReturn($fooValue);

        $barValue = $this->faker->unique()->uuid;
        $bar = $this->makePropertyMock();
        $bar->shouldReceive('getValue')
            ->andReturn($barValue);

        $weeValue = $this->faker->unique()->uuid;
        $weee = $this->makePropertyMock();
        $weee->shouldReceive('getValue')
            ->andReturn($weeValue);

        $data = [
            'foo' => $foo,
            'bar' => $bar,
            'weee' => $weee,
        ];
        $collection = $this->makeTemplatePropertiesMock($data);

        // Get the directory handler
        $handler = $this->makeTemplateHandler($log, $collection);

        // Process template
        $handler->processTemplate($template);

        // Assert
        $expectedData = [
            'foo' => $fooValue,
            'bar' => $barValue,
            'weee' => $weeValue,
        ];

        $this->assertPathsOrFilesExist([$destination], 'Destination file was not created by handler');
        $this->assertFileContainsData($destination, $expectedData);
    }

    /**
     * @test
     *
     * @covers ::processElement
     *
     * @covers ::setupEngine
     * @covers ::getEngineOptions
     * @covers ::generateFileFrom
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotProcessTemplateException
     */
    public function itFailsWhenSourceTemplateDoesNotExist()
    {
        // Make a template
        $templateId = 'myFile';
        $source = $this->faker->uuid . '.txt.twig';
        $destination= 'myFile.txt';
        $template = $this->makeTemplateWith($templateId, $source, $destination);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeTemplateHandler($log);

        // Process template
        $handler->processElement($template);
    }

    /**
     * @test
     *
     * @covers ::processTemplate
     * @covers ::processElement
     *
     * @covers ::setupEngine
     * @covers ::getEngineOptions
     * @covers ::generateFileFrom
     */
    public function itSkipsFileIfAlreadyExists()
    {
        // Make a template
        $templateId = 'myFile';
        $source = 'myFile.txt.twig';
        $destination= 'myFile.txt';
        $template = $this->makeTemplateWith($templateId, $source, $destination);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        // Get the directory handler
        $handler = $this->makeTemplateHandler($log);

        // Process template
        $handler->processTemplate($template);

        // At this point, a file is expected to exist. Therefore, re-invoking
        // the process template should result in no overwrite!
        $log = $this->makeLogMock();
        $log->shouldReceive('notice')
            ->withAnyArgs();
        $log->shouldNotReceive('info');

        $handler->setLog($log);

        $handler->processTemplate($template);
    }

    /**
     * @test
     *
     * @covers ::processElement
     *
     * @covers ::setupEngine
     * @covers ::getEngineOptions
     * @covers ::generateFileFrom
     * @covers ::generateFile
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotProcessTemplateException
     */
    public function itFailsWhenUnableToWriteToTheDisk()
    {
        // Make a template
        $templateId = 'myFile';
        $source = 'myFile.txt.twig';
        $destination= 'myFile.txt';
        $template = $this->makeTemplateWith($templateId, $source, $destination);

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        $fs = $this->makeFilesystemMock();
        $fs->shouldReceive('exists')
            ->with($this->dataPath() . $source)
            ->andReturn(true);

        $fs->shouldReceive('exists')
            ->with($this->outputPath() . $destination)
            ->andReturn(false);

        // This should make the handler fail
        $fs->shouldReceive('append')
            ->withAnyArgs()
            ->andReturn(false);

        // Get the directory handler
        $handler = $this->makeTemplateHandler($log);
        $handler->setFile($fs);

        // Process template
        $handler->processElement($template);
    }
}