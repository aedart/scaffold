<?php

namespace Aedart\Scaffold\Console\Style;

use ReflectionClass;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Extended Style
 *
 * <br />
 *
 * Custom style that allows setting the question helper instance.
 *
 * @see https://github.com/symfony/symfony/issues/19753
 * @see \Symfony\Component\Console\Style\SymfonyStyle
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Console\Style
 */
class ExtendedStyle extends SymfonyStyle
{
    /**
     * Set the given question helper
     *
     * @param SymfonyQuestionHelper $helper Instance of a Symfony Question Helper
     *
     * @return void
     */
    public function setQuestionHelper($helper)
    {
        $questionHelper = $this->getQuestionHelperProperty();
        $questionHelper->setValue($this, $helper);
    }

    /**
     * Get the given question helper
     *
     * If no question helper has been set, this method will
     * set and return a default question helper, if any such
     * value is available
     *
     * @see getDefaultQuestionHelper()
     *
     * @return SymfonyQuestionHelper|null question helper or null if none question helper has been set
     */
    public function getQuestionHelper()
    {
        $questionHelper = $this->getQuestionHelperProperty();

        $value = $questionHelper->getValue($this);
        if(!isset($value)){
            $this->setQuestionHelper($this->getDefaultQuestionHelper());
        }

        return $questionHelper->getValue($this);
    }

    /**
     * Get a default question helper value, if any is available
     *
     * @return SymfonyQuestionHelper|null A default question helper value or Null if no default value is available
     */
    public function getDefaultQuestionHelper()
    {
        return new SymfonyQuestionHelper();
    }

    /**
     * Returns reflection of the parent class
     *
     * @return ReflectionClass
     */
    protected function getParentClass()
    {
        return (new ReflectionClass($this))->getParentClass();
    }

    /**
     * Returns the reflection of the question helper property
     *
     * @return \ReflectionProperty
     */
    protected function getQuestionHelperProperty()
    {
        $questionHelperProperty = $this->getParentClass()->getProperty('questionHelper');
        $questionHelperProperty->setAccessible(true);

        return $questionHelperProperty;
    }
}