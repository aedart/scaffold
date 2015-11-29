<?php
use Aedart\Scaffold\Engines\Twig;

/**
 * Class TwigTest
 *
 * @group engines
 * @group twig
 * @coversDefaultClass Aedart\Scaffold\Engines\Twig
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
class TwigTest extends EngineTestCase {

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Get the source location of where the templates are
     * located
     *
     * @return string Relative path inside the '_data/handlers/templates/'
     */
    public function templatesLocation() {
        return 'twig';
    }

    /**
     * Get the output location of where rendered templates
     * must be written to
     *
     * @return string Relative path inside the '_output/handlers/templates/'
     */
    public function templatesOutputLocation() {
        return 'twig';
    }

    /**
     * Get the engine instance
     *
     * @return Twig
     */
    public function getEngine() {
        $engine = new Twig();

        $engine->setBasePath($this->getTemplatesLocation());

        return $engine;
    }

    /***********************************************************
     * Actual tests
     **********************************************************/

    /**
     * @test
     * @covers ::setup
     */
    public function canSetupEngine(){
        $this->assertCanPerformSetup($this->getEngine());
    }

    /**
     * @test
     * @covers ::engine
     */
    public function canObtainRawEngine() {
        $this->assertCanObtainRawEngine($this->getEngine());
    }

    /**
     * @test
     * @covers ::render
     */
    public function canRenderATemplate() {
        $data = [
            'name'          => $this->faker->word,
            'author_name'   => $this->faker->name,
            'author_email'  => $this->faker->email
        ];

        $this->assertCanRenderTemplate($this->getEngine(), 'composer.json.twig', $data, $data);
    }
}