<?php

use Aedart\Scaffold\Data\Scaffold;

/**
 * Class ScaffoldTest
 *
 * NOTE: Data-validation falls outside the scope of the given DTO
 *
 * @group data
 * @group dto
 * @coversDefaultClass Aedart\Scaffold\Data\Scaffold
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class ScaffoldTest extends DtoTestCase{

    /**
     * @test
     */
    public function canPopulateInstance() {
        $data = [
            'name'      => $this->faker->words(3, true),

            'description'   => $this->faker->sentence,

            'basePath' => __DIR__ . '/' . $this->faker->word,

            'directoryHandler' => $this->faker->word . '/' . $this->faker->word . '/' . $this->faker->word,

            'templates' => [

                [
                    'id'            =>  $this->faker->word . '.twig',
                    'handler'       =>  $this->faker->word . '/' . $this->faker->word . '/' . $this->faker->word,
                    'filename'      =>  [
                        'id'            => $this->faker->unique()->word,
                        'ask'           => $this->faker->boolean(),
                        'description'   => $this->faker->sentence,
                        'default'       => $this->faker->word . '.' . $this->faker->fileExtension
                    ]
                ],

                [
                    'id'            =>  $this->faker->word . '.tpl',
                    'handler'       =>  $this->faker->word . '/' . $this->faker->word . '/' . $this->faker->word
                ],
            ],

            'data'      => [

                [
                    'id'            => $this->faker->unique()->word,
                    'ask'           => $this->faker->boolean(),
                    'description'   => $this->faker->sentence,
                    'default'       => $this->faker->word . '.' . $this->faker->fileExtension
                ],

                [
                    'id'            => $this->faker->unique()->word,
                    'ask'           => $this->faker->boolean(),
                    'description'   => $this->faker->sentence,
                    'default'       => $this->faker->word . '.' . $this->faker->fileExtension
                ],

                [
                    'id'            => $this->faker->unique()->word,
                    'ask'           => $this->faker->boolean(),
                ],

            ],
        ];

        $scaffold = new Scaffold($data);

        // We cannot just match the array like this, because the output array does
        // contain a few differences... Therefore, we must match elements
        // individually
        //$this->assertSame($data, $scaffold->toArray());

        // Check name
        $this->assertSame($data['name'], $scaffold->getName(), 'Incorrect name set');

        // Check description
        $this->assertSame($data['description'], $scaffold->getDescription(), 'Incorrect description set');

        // Check base path
        $this->assertSame($data['basePath'], $scaffold->getBasePath(), 'Incorrect base path set');

        // Check directory handler
        $this->assertSame($data['directoryHandler'], $scaffold->getDirectoryHandler(), 'Incorrect directory handler set');

        // Check templates (count only)
        $this->assertSame(count($data['templates']), $scaffold->getTemplates()->count(), 'Incorrect templates set');

        // Check data (count only)
        $this->assertSame(count($data['data']), $scaffold->getData()->count(), 'Incorrect data set');
    }

}