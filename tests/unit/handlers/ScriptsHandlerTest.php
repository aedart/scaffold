<?php

use Aedart\Scaffold\Handlers\ScriptsHandler;
use Aedart\Scaffold\Scripts\CliScript;
use Codeception\Util\Debug;
use Illuminate\Contracts\Logging\Log;

/**
 * ScriptHandlerTest
 *
 * @group handlers
 * @group scriptsHandler
 * @coversDefaultClass Aedart\Scaffold\Handlers\ScriptsHandler
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ScriptsHandlerTest extends BaseUnitTest
{
    protected function _after()
    {
        $this->emptyPath($this->outputPath());

        parent::_after();
    }

    public function dataPath()
    {
        return parent::dataPath() . 'handlers/script/';
    }

    public function outputPath()
    {
        return parent::outputPath() . 'handlers/script/';
    }

    /**
     * Returns a new Script handler instance
     *
     * @param Log|null $log [optional]
     *
     * @return ScriptsHandler
     */
    public function makeScriptsHandler(Log $log = null)
    {
        $handler = new ScriptsHandler();

        $handler->setBasePath($this->dataPath());
        $handler->setOutputPath($this->outputPath());
        $handler->setFile($this->getFilesystem());

        if(!is_null($log)){
            $handler->setLog($log);
        }

        return $handler;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
 * @test
 */
    public function canExecuteScripts()
    {
        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        $targetA = $this->outputPath() . 'lsOutput.txt';
        $targetB = $this->outputPath() . 'lsAhlOutput.txt';

        $handler = $this->makeScriptsHandler($log);

        $scripts = [
            new CliScript([
                'script' => 'ls > ' . $targetA
            ]),
            new CliScript([
                'script' => 'ls -ahl > ' . $targetB
            ]),
        ];

        $handler->processElement($scripts);

        $this->assertFileExists($targetA, 'First script did not output!');
        $this->assertFileExists($targetB, 'Second script did not output!');

        $contentA = file_get_contents($targetA);
        $contentB = file_get_contents($targetB);

        Debug::debug($contentA);
        Debug::debug($contentB);

        $this->assertNotEmpty($contentA, 'First output file has no content');
        $this->assertNotEmpty($contentB, 'Second output file has no content');
    }

    /**
     * @test
     *
     * @expectedException \Aedart\Scaffold\Exceptions\ScriptFailedException
     */
    public function failsOnScriptFailure()
    {

        // Get a log mock
        $log = $this->makeLogMock();
        $log->shouldReceive('info')
            ->withAnyArgs();

        $log->shouldReceive('error')
            ->withAnyArgs();

        $handler = $this->makeScriptsHandler($log);

        $scripts = [
            new CliScript([
                'script' => 'blablablabalba'
            ]),
        ];

        $handler->processElement($scripts);
    }
}