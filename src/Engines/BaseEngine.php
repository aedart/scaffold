<?php namespace Aedart\Scaffold\Engines;

use Aedart\Model\Traits\Arrays\DataTrait;
use Aedart\Model\Traits\Strings\BasePathTrait;
use Aedart\Model\Traits\Strings\TemplateTrait;
use Aedart\Scaffold\Contracts\Engines\TemplateEngine;
use Aedart\Scaffold\Exceptions\CannotRenderTemplateException;
use Exception;

/**
 * <h1>Base Engine</h1>
 *
 * A base abstraction for all template engines
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Engines
 */
abstract class BaseEngine implements TemplateEngine{

    use BasePathTrait, TemplateTrait, DataTrait;

    /**
     * Render the given template, with all of its data, and
     * return the rendered string
     *
     * @return string
     *
     * @throws CannotRenderTemplateException In case that given template cannot be rendered
     */
    public function render() {
        try {
            return $this->doRender();
        } catch (Exception $e){
            throw new CannotRenderTemplateException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Render the given template, with all of its data, and
     * return the rendered string
     *
     * @return string Rendered template
     */
    abstract public function doRender();
}