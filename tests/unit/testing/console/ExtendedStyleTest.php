<?php

use Aedart\Scaffold\Testing\Console\Style\ExtendedStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ExtendedStyleTest
 *
 * @group testing
 * @group testing-console
 * @group extendedStyle
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ExtendedStyleTest extends BaseUnitTest
{

    /*************************************************************
     * Helpers
     ************************************************************/

    /**
     * Returns a new instance of an extended style
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return ExtendedStyle
     */
    public function makeExtendedStyle(InputInterface $input, OutputInterface $output)
    {
        return new ExtendedStyle($input, $output);
    }

    /*************************************************************
     * Actual tests
     ************************************************************/

    /**
     * @test
     */
    public function canObtainInstance()
    {
        $input = new ArrayInput([]);
        $output = $this->makeStreamOutput();

        $style = $this->makeExtendedStyle($input, $output);

        $this->assertNotNull($style);
    }

    /**
     * @test
     */
    public function canObtainDefaultQuestionHelper()
    {
        $input = new ArrayInput([]);
        $output = $this->makeStreamOutput();

        $style = $this->makeExtendedStyle($input, $output);

        $helper = $style->getQuestionHelper();

        $this->assertNotNull($helper);
    }

    /**
     * @test
     */
    public function canSetInputStream()
    {
        $name = $this->faker->name;

        $input = new ArrayInput([]);
        $output = $this->makeStreamOutput();

        $style = $this->makeExtendedStyle($input, $output);

        $helper = $style->getQuestionHelper();
        $helper->setInputStream($this->writeInput([$name]));

        $answer = $style->ask('Does this work?');

        $this->assertSame($name, $answer, 'Incorrect answer given!');
    }
}