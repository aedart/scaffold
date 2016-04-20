<?php

use Aedart\Scaffold\Console\BaseCommand;
use Mockery as m;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class BaseCommandTest
 *
 * @group console
 * @group baseCommand
 *
 * @coversDefaultClass Aedart\Scaffold\Console\BaseCommand
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class BaseCommandTest extends BaseUnitTest
{
    /**
     * Returns an instance of a BaseCommand mock
     *
     * @param string $name
     *
     * @return m\Mock|BaseCommand
     */
    public function makeBaseCommand($name)
    {
        return m::mock(BaseCommand::class, [$name])->makePartial();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    // TODO: It appears that it's not possible to mock the command - Mockery yield error, cannot find setName() on mock!?

//    /**
//     * @test
//     *
//     * @covers ::execute
//     * @covers ::runCommand
//     */
//    public function canExecuteRunCommand()
//    {
//        $name = 'baseCommand';
//        $baseCommand = $this->makeBaseCommand($name);
//        $baseCommand->shouldReceive('runCommand')
//            ->andReturn(0);
//
//        $app = new Application();
//        $app->add($baseCommand);
//
//        $command = $app->find($name);
//        $commandTester = new CommandTester($command);
//        $commandTester->execute([
//           'command' => $command->getName()
//        ]);
//    }
}