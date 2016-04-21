<?php

use Aedart\Scaffold\Transformers\DirectoryPaths;

/**
 * Class DirectoryPathsTest
 *
 * @group transformers
 * @group directoryPaths
 *
 * @coversDefaultClass Aedart\Scaffold\Transformers\DirectoryPaths
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class DirectoryPathsTest extends BaseUnitTest
{

    /**
     * Returns a directory list (multidimensional array)
     *
     * @return array
     */
    public function makeMultidimensionalDirectoriesList()
    {
        // This is rather static - but it will do the trick
        return [
            'app',
            'config',
            'src' =>    [
                'Contracts' => [
                    'Models' => [
                        'Users',
                        'Lists' => [
                            'UsersCollections'
                        ],
                        'Factories'
                    ],
                    'Events',
                    'Controllers',
                ],
                'Controllers',
                'Events',
                'Models',
            ],
        ];
    }

    /********************************************************
     * Actual tests
     *******************************************************/

    /**
     * @test
     *
     * @covers ::transform
     */
    public function canFlattenDirectoriesList()
    {
        $expected = [
            'app',
            'config',
            'src/Contracts/Models/Users',
            'src/Contracts/Models/Lists/UsersCollections',
            'src/Contracts/Models/Factories',
            'src/Contracts/Events',
            'src/Contracts/Controllers',
            'src/Controllers',
            'src/Events',
            'src/Models',
        ];

        $result = DirectoryPaths::transform($this->makeMultidimensionalDirectoriesList());

        $this->assertSame($expected, $result);
    }
}