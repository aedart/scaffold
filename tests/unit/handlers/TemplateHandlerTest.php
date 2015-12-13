<?php

use Aedart\Scaffold\Engines\Twig;
use Aedart\Scaffold\Handlers\TemplateHandler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use \Mockery as m;

/**
 * Class TemplateHandlerTest
 *
 * @group handlers
 * @coversDefaultClass Aedart\Scaffold\Handlers\TemplateHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplateHandlerTest extends HandlerTestCase{

    protected function _before() {
        parent::_before();

        // Add output location directory, in case it doesn't exist
        $this->createLocation($this->getOutputTemplateLocation());
    }

    protected function _after(){
        // Remove any eventual directories inside the output location
        $this->removeLocation($this->getOutputTemplateLocation());

        parent::_after();
    }

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the handler in question
     *
     * @return TemplateHandler
     */
    public function getTemplateHandler() {
        $handler = new TemplateHandler();

        $handler->setBasePath($this->getTemplateLocation());
        $handler->setOutputPath($this->getOutputTemplateLocation());

        $handler->setFile(new Filesystem());
        $handler->setTemplateEngine(new Twig());

        return $handler;
    }

    /**
     * Get a filesystem mock
     *
     * @return m\MockInterface|Filesystem
     */
    public function getFilesystemMock() {
        return m::mock(Filesystem::class);
    }

    /***********************************************************
     * Utility tests
     *      These are not a part of the template-handler
     *      interface and might be subject to change!
     **********************************************************/

    /**
     * @test
     * @covers ::computeOutputPath
     */
    public function canComputeRelativeOutputPath() {
        $template = $this->getTemplateLocation() . 'Modules/module.php.twig';
        $filename = Str::camel($this->faker->word) . '.php';

        $handler = $this->getTemplateHandler();

        $expectedOutputPath = $this->getOutputTemplateLocation() . 'Modules/' . $filename;

        $result = $handler->computeOutputPath($template, $filename);

        $this->assertSame($expectedOutputPath, $result, 'Incorrect output path computation');
    }

    /**
     * This test might be a bit redundant, yet had to make sure that "deep"
     * nested templates would still receive the correct output path
     *
     * @test
     * @covers ::computeOutputPath
     */
    public function canComputeDeepNestedOutputPath() {
        $template = $this->getTemplateLocation() . 'Components/Api/Models/model.txt.twig';
        $filename = Str::camel($this->faker->word) . '.txt';

        $handler = $this->getTemplateHandler();

        $expectedOutputPath = $this->getOutputTemplateLocation() . 'Components/Api/Models/' . $filename;

        $result = $handler->computeOutputPath($template, $filename);

        $this->assertSame($expectedOutputPath, $result, 'Incorrect output path computation');
    }

    /**
     * @test
     * @covers ::writeFile
     *
     * @expectedException \Aedart\Scaffold\Exceptions\FileAlreadyExistsException
     */
    public function failsWritingFileIfAlreadyExists() {
        $fs = $this->getFilesystemMock();
        $fs->shouldReceive('exists')
            ->withAnyArgs()
            ->andReturn(true);

        $handler = $this->getTemplateHandler();
        $handler->setFile($fs);

        $handler->writeFile($this->faker->word, $this->faker->sentence);
    }

    /**
     * @test
     * @covers ::writeFile
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CouldNotCreateFileException
     */
    public function failsWhenUnableToWriteFile() {
        $fs = $this->getFilesystemMock();
        $fs->shouldReceive('exists')
            ->withAnyArgs()
            ->andReturn(false);

        $fs->shouldReceive('put')
            ->withAnyArgs()
            ->andReturn(false);

        $handler = $this->getTemplateHandler();
        $handler->setFile($fs);

        $handler->writeFile($this->faker->word, $this->faker->sentence);
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::handle
     * @covers ::processElement
     */
    public function canProcessTemplateAndCreateFile() {
        // Ready the directories and template
        $dir = 'Components/Api/Models/';
        $this->createLocation($this->getOutputTemplateLocation() . $dir);
        $element = $dir . 'model.txt.twig';

        // NOTE: This "data" is in the correct format, which is
        // required by the template engine (key => value pairs)
        $data = [
            'alpha' => $this->faker->address,
            'beta'  => $this->faker->uuid,
            'gamma' => $this->faker->name
        ];

        // Output filename
        $filename = $this->faker->word . '.txt';

        // Setup the handler
        $handler = $this->getTemplateHandler();
        $handler->setFilename($filename);
        $handler->setData($data);

        // Handle!
        $handler->handle($element);

        // Does file exists
        $expectedOutputPath = $this->getOutputTemplateLocation() . 'Components/Api/Models/' . $filename;
        $this->assertFileExists($expectedOutputPath, 'No file was created');

        // Does file contain the data
        $content = file_get_contents($expectedOutputPath);
        foreach($data as $k => $v){
            $this->assertContains($v, $content, sprintf('Generated file does not contain data (%s => %s)', $k, $v));
        }
    }

    /**
     * In this test, we provide too many data-properties for the template,
     * yet do still expect the engine not to fail!
     *
     * @test
     * @covers ::handle
     * @covers ::processElement
     */
    public function canProcessTemplateWhenTooManyDataPropertiesGiven() {
        // Ready the directories and template
        $dir = 'Components/Api/Models/';
        $this->createLocation($this->getOutputTemplateLocation() . $dir);
        $element = $dir . 'model.txt.twig';

        // NOTE: This "data" is in the correct format, which is
        // required by the template engine (key => value pairs)
        $data = [
            'alpha' => $this->faker->address,
            'beta'  => $this->faker->uuid,
            'gamma' => $this->faker->name,

            // These are NOT found in the template
            'zeta'  => $this->faker->boolean(),
            'grim'  => $this->faker->century
        ];

        // Output filename
        $filename = $this->faker->word . '.txt';

        // Setup the handler
        $handler = $this->getTemplateHandler();
        $handler->setFilename($filename);
        $handler->setData($data);

        // Handle!
        $handler->handle($element);

        // Does file exists
        $expectedOutputPath = $this->getOutputTemplateLocation() . 'Components/Api/Models/' . $filename;
        $this->assertFileExists($expectedOutputPath, 'No file was created');
    }
}