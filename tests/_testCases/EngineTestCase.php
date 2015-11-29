<?php
use Aedart\Scaffold\Contracts\Engines\TemplateEngine;
use Codeception\Configuration;

/**
 * Engine Test Case
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 */
abstract class EngineTestCase extends HandlerTestCase{

    protected function _before() {
        parent::_before();

        // Add output location directory, in case it doesn't exist
        $this->createLocation($this->getTemplatesLocation());
    }

    protected function _after(){
        // Remove any eventual directories inside the output location
        $this->removeLocation($this->getOutputTemplateLocation());

        parent::_after();
    }

    /***********************************************************
     * Abstract methods
     **********************************************************/

    /**
     * Get the source location of where the templates are
     * located
     *
     * @return string Relative path inside the '_data/handlers/templates/'
     */
    abstract public function templatesLocation();

    /**
     * Get the output location of where rendered templates
     * must be written to
     *
     * @return string Relative path inside the '_output/handlers/templates/'
     */
    abstract public function templatesOutputLocation();

    /***********************************************************
     * Helpers and utilities
     **********************************************************/

    /**
     * Returns the location from where files can be copied
     *
     * @return string
     */
    public function getTemplatesLocation() {
        return Configuration::dataDir() . 'handlers/templates/' . $this->templatesLocation();
    }

    /**
     * Returns location of where files are to be copied to
     *
     * @return string
     */
    public function getOutputTemplateLocation() {
        return Configuration::outputDir() . 'handlers/templates/' . $this->templatesOutputLocation();
    }

    /***********************************************************
     * Assertions
     **********************************************************/

    /**
     * Assert that the given template engine's setup method can be
     * invoked, without any exceptions
     *
     * @param TemplateEngine $engine
     */
    public function assertCanPerformSetup(TemplateEngine $engine) {
        try {
            $engine->setup();

            $this->assertTrue(true);
        } catch (\Exception $e){
            $this->fail($e);
        }
    }

    /**
     * Assert that the given engine wrapper is able to return its
     * underlying raw engine instance
     *
     * WARNING: method will trigger `setup()`, before attempting
     * get the raw engine instance
     *
     * @param TemplateEngine $engine
     */
    public function assertCanObtainRawEngine(TemplateEngine $engine) {
        $engine->setup();

        $rawEngine = $engine->engine();

        $this->assertNotNull($rawEngine, 'Expected an object instance');
    }

    /**
     * Assert that the given template engine is able to render and return
     * a given template, with the provided data
     *
     * @param TemplateEngine $engine
     * @param string $template Filename of the template in question
     * @param array $data Key-value pairs of the variables to be rendered
     * @param array $expectedRenderedValues Key-value pair of expected rendered values that MUST be found in the
     *                                      rendered output
     */
    public function assertCanRenderTemplate(TemplateEngine $engine, $template, array $data, array $expectedRenderedValues) {
        $engine->setTemplate($template);

        $engine->setData($data);

        $engine->setup();

        $output = $engine->render();

        $this->assertInternalType('string', $output, 'Rendered output should be a string');
        $this->assertNotEmpty($output, 'Rendered output should not be empty');

        foreach($expectedRenderedValues as $id => $value){
            $this->assertContains($value, $output, sprintf('%s (%s) is not in the rendered template', $id, var_export($value, true)));
        }
    }
}