<?php
namespace Aedart\Scaffold\Testing\Console\Style;

use ReflectionClass;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Extended Style
 *
 * <br />
 *
 * WARNING: This class is used as a workaround to the issue of not being
 * able to test interaction when using SymfonyStyle.
 *
 * <br />
 *
 * It gives the possibility of setting and getting the <i>private</i>
 * <code>$questionHelper</code>, which is needed if you wish to set
 * the input stream for your tests. This is achieved by use of reflections
 * and it is NOT recommended, because it is basically a hack!
 *
 * <br />
 *
 * <b>DO NOT USE THIS FOR PRODUCTION!</b>
 *
 * @see https://github.com/symfony/symfony/issues/19753
 * @see \Symfony\Component\Console\Style\SymfonyStyle
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Testing\Console\Style
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