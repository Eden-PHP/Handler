<?php //-->
/**
 * This file is part of the Eden package.
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
 * @category handler
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Error extends Base
{
    const INSTANCE     = 1;
    
    //error type
    const PHP         = 'PHP'; //used when argument is invalidated
    const UNKNOWN     = 'UNKNOWN';

    //error level
    const WARNING     = 'WARNING';
    const ERROR     = 'ERROR';
    
    /**
     * Called when a PHP error has occured. Must
     * use setErrorHandler() first.
     *
     * @param  *number error number
     * @param  *string message
     * @param  *string file
     * @param  *string line
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
     * @return this
     */
    public function release()
    {
        restore_error_handler();
        return $this;
    }

    /**
     * Registers this class' error handler to PHP
     *
     * @return this
     */
    public function register()
    {
        set_error_handler(array($this, 'handle'));
        return $this;
    }

    /**
     * Sets reporting
     *
     * @param  *int
     *
     * @return this
     */
    public function setReporting($type)
    {
        error_reporting($type);
        return $this;
    }
}
