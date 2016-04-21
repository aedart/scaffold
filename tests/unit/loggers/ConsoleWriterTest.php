<?php

use Aedart\Scaffold\Loggers\ConsoleWrite;
use Mockery as m;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleWriterTest
 *
 * @group loggers
 * @group consoleWriter
 *
 * @coversDefaultClass Aedart\Scaffold\Loggers\ConsoleWrite
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ConsoleWriterTest extends ConsoleTest
{

    /**
     * Returns a new Console Writer instance
     *
     * @param OutputInterface $output
     *
     * @return ConsoleWrite
     */
    public function makeConsoleWriter(OutputInterface $output)
    {
        return new ConsoleWrite($output);
    }

    /**
     * Data provider for log messages at various levels
     *
     * @return array
     */
    public function logDataProvider()
    {
        return [
            [LogLevel::DEBUG, 'debug {msg}', ['msg' => 'message']],
            [LogLevel::INFO, 'info {msg}', ['msg' => 'message']],
            [LogLevel::NOTICE, 'notice {msg}', ['msg' => 'message']],
            [LogLevel::WARNING, 'warning {msg}', ['msg' => 'message']],
            [LogLevel::ERROR, 'error {msg}', ['msg' => 'message']],
            [LogLevel::CRITICAL, 'critical {msg}', ['msg' => 'message']],
            [LogLevel::ALERT, 'alert {msg}', ['msg' => 'message']],
            [LogLevel::EMERGENCY, 'emergency {msg}', ['msg' => 'message']],
        ];
    }
    
    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function canObtainInstance()
    {
        $writer = $this->makeConsoleWriter($this->makeOutputMock());

        $this->assertNotNull($writer);
    }

    /**
     * @test
     *
     * @dataProvider logDataProvider
     *
     * @covers ::log
     *
     * @covers ::interpolate
     *
     * @param string $level
     * @param string $message
     * @param array $context [optional]
     */
    public function canWriteToOutput($level, $message, array $context = [])
    {
        $output = $this->makeOutputMock();
        $output->shouldReceive('writeln')
            ->once()
            ->with(m::type('string'));

        $writer = $this->makeConsoleWriter($output);
        
        $writer->log($level, $message, $context);
    }
}