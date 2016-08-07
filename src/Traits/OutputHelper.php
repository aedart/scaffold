<?php
namespace Aedart\Scaffold\Traits;

use Symfony\Component\Console\Style\StyleInterface;

/**
 * Output Helper
 *
 * Utility for writing to the console
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Traits
 */
trait OutputHelper
{
    /**
     * The output
     *
     * @var StyleInterface
     */
    protected $output = null;

    /**
     * Set the output
     *
     * @param StyleInterface $output [optional]
     */
    public function setOutput(StyleInterface $output = null)
    {
        if(isset($output)){
            $this->output = $output;
            return;
        }
    }

    /**
     * Output a title
     *
     * @param string $title
     */
    public function outputTitle($title)
    {
        $this->output($title, 'title');
    }

    /**
     * Output a note
     *
     * @param string $note
     */
    public function outputNote($note)
    {
        $this->output($note, 'note');
    }

    /**
     * Output a message
     *
     * @param string $message
     */
    public function outputMessage($message)
    {
        $this->output($message, 'text');
    }

    /**
     * Prints a message onto the screen if any output is available
     *
     * @param string $message
     * @param string $type [optional]
     */
    protected function output($message, $type = 'note')
    {
        if(isset($this->output)){
            call_user_func([$this->output, $type], $message);
        }
    }
}