<?php

use Aedart\Scaffold\Console\BaseCommand;
use Mockery as m;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class BaseCommandTest
 *
 * @group console
 * @group baseCommand
 *
 * @coversDefaultClass Aedart\Scaffold\Console\BaseCommand
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseCommandTest extends ConsoleTest
{
    /**
     * Returns an instance of a BaseCommand mock
     *
     * @return m\Mock|BaseCommand
     */
    public function makeBaseCommand()
    {
        return m::mock(BaseCommand::class)->makePartial();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::execute
     * @covers ::runCommand
     */
    public function canExecuteRunCommand()
    {

        $input = $this->makeInputMock();
        $input->shouldDeferMissing();

        // Output is very hard to mock at this point!
        $output = new ConsoleOutput();

        $command = $this->makeBaseCommand();
        $command->shouldReceive('runCommand')
            ->once();

        $command->execute($input, $output);
    }
}