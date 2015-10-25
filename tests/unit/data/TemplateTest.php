<?php

use Aedart\Scaffold\Data\AskableProperty;
use Aedart\Scaffold\Data\Template;
use \Mockery as m;

/**
 * Class TemplateTest
 *
 * @group data
 * @group dto
 * @coversDefaultClass Aedart\Scaffold\Data\Template
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TemplateTest extends DtoTestCase{

    /******************************************************************
     * Helpers
     *****************************************************************/

    /**
     * Get a askable-property mock
     *
     * @return m\MockInterface|AskableProperty
     */
    public function getAskablePropertyMock() {
        $id = $this->faker->uuid;

        $mock = m::mock(AskableProperty::class)->makePartial();
        $mock->setId($id);

        return $mock;
    }

    /******************************************************************
     * Actual tests
     *****************************************************************/

    /**
     * @test
     * @covers ::setFilename
     * @covers ::getFilename
     */
    public function canPopulateInstance() {
        $data = [
            'filename'      => $this->getAskablePropertyMock(),
            'id'            => $this->faker->uuid,
            'handler'       => $this->faker->word . '/' . $this->faker->word,
        ];

        $template = new Template($data);

        $this->assertSame($data, $template->toArray());
    }

}