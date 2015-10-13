<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Handler;

/**
 * Error event hander
 *
 * @package  Eden
 * @category Handler
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Error extends Base
{
    /**
     * @const int INSTANCE Flag that designates singleton when using ::i()
     */
    const INSTANCE = 1;
    
    /**
     * @const int PHP Used when the problem came from PHP code
     */
    const PHP = 'PHP';
    
    /**
     * @const int UNKNOWN Used when we don't know exactly the source
     */
    const UNKNOWN = 'UNKNOWN';

    /**
     * @const int WARNING Used when we want to output the error but continue
     */
    const WARNING = 'WARNING';
    
    /**
     * @const int ERROR Used when we want to output the error and exit
     */
    const ERROR = 'ERROR';
    
    /**
     * Called when a PHP error has occured. Must
     * use setErrorHandler() first.
     *
     * @param *number $errno   Error number
     * @param *string $errstr  Error message
     * @param *string $errfile Error file name where it was triggered
     * @param *string $errline Error line where it was triggered
     *
     * @return true
     */
    public function handle($errno, $errstr, $errfile, $errline)
    {
        //depending on the error number
        //we can determine the error level
        switch ($errno) {
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_WARNING:
            case E_USER_WARNING:
                $level = self::WARNING;
                break;
            case E_ERROR:
            case E_USER_ERROR:
            default:
                $level = self::ERROR;
                break;
        }

        //errors are only triggered through PHP
        $type = self::PHP;

        //get the trace
        $trace = debug_backtrace();

        //by default we do not know the class
        $class = self::UNKNOWN;

        //if there is a trace
        if (count($trace) > 1) {
            //formulate the class
            $class = $trace[1]['function'].'()';
            if (isset($trace[1]['class'])) {
                $class = $trace[1]['class'].'->'.$class;
            }
        }

        $this->trigger(
            'error',
            $type,
            $level,
            $class,
            $errfile,
            $errline,
            $errstr,
            $trace,
            1
        );

        //Don't execute PHP internal error handler
        return true;
    }

    /**
     * Returns default handler back to PHP
     *
     * @return Eden\Handler\Error
     */
    public function release()
    {
        restore_error_handler();
        return $this;
    }

    /**
     * Registers this class' error handler to PHP
     *
     * @return Eden\Handler\Error
     */
    public function register()
    {
        set_error_handler(array($this, 'handle'));
        return $this;
    }

    /**
     * Sets reporting
     *
     * @param *int $type type
     *
     * @return Eden\Handler\Error
     */
    public function setReporting($type)
    {
        error_reporting($type);
        return $this;
    }
}
