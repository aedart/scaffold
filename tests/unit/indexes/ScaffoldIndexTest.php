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
     * List of vendors
     *
     * @var string[]
     */
    protected $vendorList = [
        'acme',
        'bobs',
        'tmps'
    ];

    /**
     * List of packages
     *
     * @var string[]
     */
    protected $packageList = [
        'lipsum',
        'scaffolds',
        'tests',
        'resources'
    ];

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
            'vendor'        => $this->makeVendor(),
            'package'       => $this->makePackage(),
            'name'          => $this->faker->name,
            'description'   => $this->faker->sentence,
            'filePath'      => $this->faker->word . '.scaffold.php'
        ]);

        return $location;
    }

    /**
     * Returns a vendor name
     *
     * @return string
     */
    public function makeVendor()
    {
        return $this->faker->randomElement($this->vendorList);
    }

    /**
     * Returns a package name
     *
     * @return string
     */
    public function makePackage()
    {
        return $this->faker->randomElement($this->packageList);
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
     * @covers ::count
     *
     * @covers ::makeVendorKey
     * @covers ::makeLocationKey
     * @covers ::makePackageKey
     */
    public function canRegisterLocations()
    {
        $locations = $this->makeLocationsList();
        $index = $this->makeScaffoldIndex($locations);

        $this->assertCount(count($locations), $index);
    }


}