<?php

use Aedart\Scaffold\Scripts\CliScript;

/**
 * CliScriptTest
 *
 * @group scripts
 * @group cli-script
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class CliScriptTest extends BaseUnitTest
{
    /**
     * Returns a new instance of Property
     *
     * @return CliScript
     */
    public function makeCliScript()
    {
        return new CliScript();
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     */
    public function canObtainInstance()
    {
        $script = $this->makeCliScript();

        $this->assertNotNull($script);
    }

    /**
     * @test
     */
    public function hasDefaultTimeout()
    {
        $script = $this->makeCliScript();

        $this->assertNotNull($script->getTimeout());
    }
}