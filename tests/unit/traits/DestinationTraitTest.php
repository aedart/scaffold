<?php
use Aedart\Scaffold\Traits\Destination;

/**
 * Class DestinationTraitTest
 *
 * @group traits
 * @group destination
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DestinationTraitTest extends TraitsTestCase
{
    use PropertyUtil;

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     */
    public function canInvokeTraitMethods()
    {
        $this->assertGetterSetterTraitMethods(Destination::class, $this->makePropertyMock(), $this->makePropertyMock());
    }
}