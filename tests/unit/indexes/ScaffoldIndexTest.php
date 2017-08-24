<?php

use Aedart\Scaffold\Indexes\ScaffoldIndex;
use Aedart\Scaffold\Indexes\Location;
use Carbon\Carbon;
use Codeception\Util\Debug;

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
        'tmps',
        'tule',
        'can',
        'org',
        'com',
        'dk'
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
     * Returns a list of scaffold locations
     *
     * @param int $amount [optional]
     * @return Location[]
     */
    public function makeScaffoldLocationList($amount = 3)
    {
        $output = [];

        while($amount--){
            $output[] = $this->makeScaffoldLocation();
        }

        return $output;
    }

    /**
     * Returns an array representation of a scaffold location
     *
     * @return array
     */
    public function makeScaffoldLocationArray()
    {
        return $this->makeScaffoldLocation()->toArray();
    }

    /**
     * Returns a list of scaffold locations - array representation
     *
     * @param int $amount [optional]
     * @return Location[]
     */
    public function makeScaffoldLocationArrayList($amount = 3)
    {
        $output = [];

        while($amount--){
            $output[] = $this->makeScaffoldLocationArray();
        }

        return $output;
    }

    /**
     * Returns a vendor name
     *
     * @return string
     */
    public function makeVendor()
    {
        return $this->faker->unique()->randomElement($this->vendorList);
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
     */
    public function canObtainInstance()
    {
        $index = $this->makeScaffoldIndex();

        $this->assertNotNull($index);
    }

    /**
     * @test
     */
    public function canRegisterLocation()
    {
        $index = $this->makeScaffoldIndex();

        $location = $this->makeScaffoldLocation();

        $index->register($location);

        $this->assertTrue($index->hasBeenRegistered($location), 'Location not included in index');
        $this->assertTrue(isset($index[$location->hash()]), 'Location not included in index - via offset');
        $this->assertSame($location, $index[$location->hash()], 'Incorrect location returned via offset');
    }


    /**
     * @test
     */
    public function canRegisterViaOffset()
    {
        $index = $this->makeScaffoldIndex();

        $location = $this->makeScaffoldLocation();

        $index[$location->hash()] = $location;

        $this->assertSame($location, $index[$location->hash()], 'Incorrect location returned via offset');
    }

    /**
     * @test
     */
    public function canUnregister()
    {
        $index = $this->makeScaffoldIndex();

        $location = $this->makeScaffoldLocation();

        $index->register($location);
        $index->unregister($location);

        $this->assertFalse($index->hasBeenRegistered($location), 'Location should not be in index');
    }

    /**
     * @test
     */
    public function canUnregisterViaOffset()
    {
        $index = $this->makeScaffoldIndex();

        $location = $this->makeScaffoldLocation();

        $index->register($location);
        unset($index[$location]);

        $this->assertFalse($index->hasBeenRegistered($location), 'Location should not be in index');
    }

    /**
     * @test
     */
    public function canPopulate()
    {
        $locations = $this->makeScaffoldLocationList();

        $index = $this->makeScaffoldIndex($locations);

        $this->assertCount(count($locations), $index, 'Incorrect amount of locations added');
    }

    /**
     * @test
     */
    public function canPopulateFromArrayRepresentationOfLocations()
    {
        $locations = $this->makeScaffoldLocationArrayList();

        $index = $this->makeScaffoldIndex($locations);

        $this->assertCount(count($locations), $index, 'Incorrect amount of locations added');
    }

    /**
     * @test
     *
     * @expectedException \Aedart\Scaffold\Exceptions\CannotPopulateIndexException
     */
    public function failsPopulatingWhenLocationTypeIsNotKnown()
    {
        $locations = $this->makeScaffoldLocationList();

        // Add 1 invalid location - is not supported by the populate method
        $locations[] = new stdClass();

        $index = $this->makeScaffoldIndex($locations);
    }

    /**
     * @test
     */
    public function canObtainAllVendors()
    {
        $vendorA = $this->faker->unique()->word;
        $vendorB = $this->faker->unique()->word;
        $vendorC = $this->faker->unique()->word;

        $locationA = $this->makeScaffoldLocation();
        $locationA->setVendor($vendorA);

        $locationB = $this->makeScaffoldLocation();
        $locationB->setVendor($vendorB);

        $locationC = $this->makeScaffoldLocation();
        $locationC->setVendor($vendorC);

        $locationD = $this->makeScaffoldLocation();
        $locationD->setVendor($vendorC);

        $locations = [
            $locationA,
            $locationB,
            $locationC,
            $locationD
        ];

        $index = $this->makeScaffoldIndex($locations);

        $vendors = $index->getVendors();

        $this->assertCount(3, $vendors, 'Incorrect amount of vendors returned');
        foreach($vendors as $vendor){

            Debug::debug($vendor);

            $this->assertTrue(in_array($vendor, [$vendorA, $vendorB, $vendorC]), 'Unknown vendor returned from index');
        }
    }

    /**
     * @test
     */
    public function canObtainAllPackages()
    {
        $vendorA = $this->faker->unique()->word;
        $vendorB = $this->faker->unique()->word;

        $packageA = $this->faker->unique()->word;
        $packageB = $this->faker->unique()->word;
        $packageC = $this->faker->unique()->word;
        $packageD = $this->faker->unique()->word;

        $locationA = $this->makeScaffoldLocation();
        $locationA->setVendor($vendorA);
        $locationA->setPackage($packageA);

        $locationB = $this->makeScaffoldLocation();
        $locationB->setVendor($vendorA);
        $locationB->setPackage($packageB);

        $locationC = $this->makeScaffoldLocation();
        $locationC->setVendor($vendorA);
        $locationC->setPackage($packageC);

        $locationD = $this->makeScaffoldLocation();
        $locationD->setVendor($vendorB);
        $locationD->setPackage($packageD);

        $locations = [
            $locationA,
            $locationB,
            $locationC,
            $locationD
        ];

        $index = $this->makeScaffoldIndex($locations);

        $packages = $index->getPackagesFor($vendorA);

        $this->assertCount(3, $packages, 'Incorrect amount of packages returned');
        foreach($packages as $package){

            Debug::debug($package);

            $this->assertTrue(in_array($package, [$packageA, $packageB, $packageC]), 'Unknown package returned from index, for vendor ' . $vendorA);
        }
    }

    /**
     * @test
     */
    public function canObtainLocationsForSpecificVendorAndPackage()
    {
        $vendorA = $this->faker->unique()->word;
        $vendorB = $this->faker->unique()->word;

        $packageA = $this->faker->unique()->word;
        $packageB = $this->faker->unique()->word;
        $packageC = $this->faker->unique()->word;

        $locationA = $this->makeScaffoldLocation();
        $locationA->setVendor($vendorA);
        $locationA->setPackage($packageA);

        $locationB = $this->makeScaffoldLocation();
        $locationB->setVendor($vendorA);
        $locationB->setPackage($packageB);

        $locationC = $this->makeScaffoldLocation();
        $locationC->setVendor($vendorA);
        $locationC->setPackage($packageB);

        $locationD = $this->makeScaffoldLocation();
        $locationD->setVendor($vendorB);
        $locationD->setPackage($packageC);

        $locations = [
            $locationA,
            $locationB,
            $locationC,
            $locationD
        ];

        $index = $this->makeScaffoldIndex($locations);

        $locations = $index->getLocationsFor($vendorA, $packageB);

        $this->assertCount(2, $locations, 'Incorrect amount of locations');
        foreach($locations as $location){

            Debug::debug($location);

            $this->assertTrue(in_array($location, [$locationB, $locationC, $packageC]), 'Unknown location returned from index, for vendor ' . $vendorA . ' and package ' . $packageB);
        }
    }

    /**
     * @test
     */
    public function canDetermineIfExpired()
    {
        $index = $this->makeScaffoldIndex();
        $this->assertFalse($index->hasExpired(), 'Should NOT have expired!');

        $index->expires((new Carbon())->subMinutes(5));
        $this->assertTrue($index->hasExpired(), 'Should have expired!');
    }

    /**
     * @test
     */
    public function canPopulateExpiresAt()
    {
        $locations = $this->makeScaffoldLocationList();

        // Add a string expires at date
        $expiresAt = (new Carbon())->addMinutes(5);

        $locations[ScaffoldIndex::EXPIRES_AT_KEY] = $expiresAt;

        $index = $this->makeScaffoldIndex($locations);

        Debug::debug('Expires at: ' . $expiresAt . ' :: index expires at: ' . $index->expiresAt());

        $this->assertTrue($index->expiresAt()->eq($expiresAt), 'Incorrect expires at date set');
    }

    /**
     * @test
     */
    public function arrayExportContainsExpiresAtKey()
    {
        $locations = $this->makeScaffoldLocationList();
        $index = $this->makeScaffoldIndex($locations);

        $arr = $index->toArray();

        Debug::debug($arr);

        $this->assertArrayHasKey(ScaffoldIndex::EXPIRES_AT_KEY, $arr, 'Expires at key is not in array');
        $this->assertNotEmpty($arr[ScaffoldIndex::EXPIRES_AT_KEY], 'No expires at value in array');
    }

    /**
     * @test
     */
    public function canExportToJson()
    {
        $locations = $this->makeScaffoldLocationList();
        $index = $this->makeScaffoldIndex($locations);

        $json = $index->toJson(JSON_PRETTY_PRINT);

        Debug::debug($json);

        $this->assertJson($json);
    }
}