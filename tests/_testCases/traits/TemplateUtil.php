<?php

use Aedart\Scaffold\Contracts\Templates\Data\Property;
use Aedart\Scaffold\Contracts\Templates\Template;
use Mockery as m;

/**
 * Trait TemplateUtil
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
trait TemplateUtil
{
    /**
     * Returns a Template Data Property mock
     *
     * @param Property $destination [optional]
     *
     * @return Template|m\MockInterface
     */
    public function makeTemplateMock(Property $destination = null)
    {
        $template = m::mock(Template::class);

        $id = $this->faker->unique()->word;

        $template->shouldReceive('getId')
            ->andReturn($id);

        $template->shouldReceive('setId')
            ->withAnyArgs();

        $template->shouldReceive('getDestination')
            ->andReturn($destination);

        return $template;
    }

    /**
     * Returns a list of mocked Templates (objects)
     *
     * @param int $amount [optional]
     *
     * @return Property[]
     */
    public function makeTemplatesList($amount = 3)
    {
        $output = [];

        while($amount--){
            $template = $this->makeTemplateMock();

            $output[$template->getId()] = $template;
        }

        return $output;
    }
}