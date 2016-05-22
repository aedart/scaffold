<?php

use Aedart\Scaffold\Indexes\ScaffoldIndex;
use Aedart\Scaffold\Indexes\Location;

/**
 * Class ScaffoldIndexTest
 *
 * @group indexes
 * @group index
 * @coversDefaultClass Aedart\Scaffold\Indexes\ScaffoldIndex
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ScaffoldIndexTest extends BaseUnitTest
{
    /**
     * Returns a new instance of the Scaffold Index
     *
     * @param Location[] $locations
     *
     * @return ScaffoldIndex
     */
    public function makeScaffoldIndex(array $locations = [])
    {
        $index = new ScaffoldIndex($locations);
        return $index;
    }

    /**
     * Returns a populated scaffold location object
     *
     * @return Location
     */
    public function makeScaffoldLocation()
    {
        $location = new Location([
            'vendor'        => $this->faker->randomElement([
                'acme',
                'bobs',
                'tmps'
            ]),
            'package'       => $this->faker->randomElement([
                'lipsum',
                'scaffolds',
                'tests',
                'resources'
            ]),
            'name'          => $this->faker->name,
            'description'   => $this->faker->sentence,
            'filePath'      => $this->faker->word . '.scaffold.php'
        ]);

        return $location;
    }

    /**
     * Returns a list of scaffold locations
     *
     * @param int $amount [optional]
     *
     * @return Location[]
     */
    public function makeLocationsList($amount = 10)
    {
        $locations = [];

        while($amount--){
            $locations[] = $this->makeScaffoldLocation();
        }

        return $locations;
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::populate
     * @covers ::register
     *
     * @covers ::registerVendor
     * @covers ::registerVendorPackage
     * @covers ::registerPackageScaffoldKey
     *
     * @covers ::makeVendorKey
     * @covers ::makeLocationKey
     * @covers ::makePackageKey
     */
    public function canRegisterLocations()
    {
        $locations = $this->makeLocationsList();
        $index = $this->makeScaffoldIndex($locations);

        // TODO: a real assert... not just die!
        dd($index->raw());

        // TODO: json conversion needs either to do so based on raw or ...what? Perhaps not!
    }
}