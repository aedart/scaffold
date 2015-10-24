<?php
use Aedart\Scaffold\Data\AskableProperty;

/**
 * Class AskablePropertyTest
 *
 * @group data
 * @group dto
 * @coversDefaultClass Aedart\Scaffold\Data\AskableProperty
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class AskablePropertyTest extends DtoTestCase{

    /**
     * @test
     * @covers ::setDefault
     * @covers ::getDefault
     */
    public function canPopulateInstance() {
        $data = [
            'default'       => $this->faker->word,
            'id'            => $this->faker->name,
            'ask'           => $this->faker->boolean(),
            'description'   => $this->faker->sentence,
        ];

        $askableProperty = new AskableProperty($data);

        $this->assertSame($data, $askableProperty->toArray());
    }

    /**
     * @test
     * @covers ::hasDefault
     */
    public function canDetermineIfADefaultValueIsAvailable() {
        $askableProperty = new AskableProperty();

        $this->assertFalse($askableProperty->hasDefault(), 'No default should be available');

        $askableProperty->default = $this->faker->address;

        $this->assertTrue($askableProperty->hasDefault(), 'A default should be available');
    }

    /**
     * @test
     * @covers ::getDefaultAsk
     */
    public function shouldNotAskForValueByDefault() {
        $askableProperty = new AskableProperty();

        $this->assertFalse($askableProperty->shouldAsk());
    }
}