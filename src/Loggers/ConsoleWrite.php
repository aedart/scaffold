<?php namespace Aedart\Scaffold\Loggers;

use Illuminate\Contracts\Logging\Log;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console Write
 *
 * Writes the various log messages directly to the console
 *
 * @see \Psr\Log\LoggerInterface
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Loggers
 */
class ConsoleWrite extends AbstractLogger implements Log
{

    /**
     * The output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * ConsoleWrite constructor.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        $msg = $this->interpolate($message, $context);

        switch($level){
            case LogLevel::EMERGENCY:
                $finalOutput = " <fg=white;bg=red>[ emergency ]</> {$msg}";
                break;

            case LogLevel::ALERT:
                $finalOutput = " <fg=white;bg=red>[ alert ]</> {$msg}";
                break;

            case LogLevel::CRITICAL:
                $finalOutput = " <fg=white;bg=red>[ critical ]</> {$msg}";
                break;

            case LogLevel::ERROR:
                $finalOutput = " <fg=white;bg=red>[ error ]</> {$msg}";
                break;

            case LogLevel::WARNING:
                $finalOutput = " <fg=black;bg=yellow>[ warning ]</> {$msg}";
                break;

            case LogLevel::NOTICE:
                $finalOutput = " <fg=black;bg=yellow>[ notice ]</> {$msg}";
                break;

            case LogLevel::INFO:
                $finalOutput = " <fg=black;bg=cyan>[ info ]</> {$msg}";
                break;

            case LogLevel::DEBUG:
            default:
                $finalOutput = " <fg=white;bg=black>[ debug ]</> {$msg}";
                break;
        }

        $this->output->writeln($finalOutput);
    }

    /**
     * Std. interpolate method from PSR
     *
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
     *
     * @param string $message
     * @param array $context [optional]
     *
     * @return string
     */
    protected function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach($context as $key => $value){
            $replace['{' . $key . '}'] = $value;
        }

        return strtr($message, $replace);
    }

    /**
     * Register a file log handler.
     *
     * @param  string $path
     * @param  string $level
     *
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {
        // TODO: Is this ever going to be supported? Is it needed?
        $this->warning('useFiles() method is not supported in current version');
        return;
    }

    /**
     * Register a daily file log handler.
     *
     * @param  string $path
     * @param  int $days
     * @param  string $level
     *
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {
        // TODO: Is this ever going to be supported? Is it needed?
        $this->warning('useDailyFiles() method is not supported in current version');
        return;
    }
}