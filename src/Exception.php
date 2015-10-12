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
 * Exception event handler
 *
 * @package  Eden
 * @category handler
 * @author   Christian Blanquera
 */
class Exception extends Base
{
    const INSTANCE = 1;
    
    /**
     * Called when a PHP exception has occured. Must
     * use setExceptionHandler() first.
     *
     * @param  *Exception
     *
     * @return void
     */
    public function handle(\Exception $e)
    {
        //by default set LOGIC ERROR
        $type         = \Eden\Core\Exception::LOGIC;
        $level         = \Eden\Core\Exception::ERROR;
        $offset     = 1;
        $reporter     = get_class($e);

        $trace         = $e->getTrace();
        $message     = $e->getMessage();

        //if the exception is an eden exception
        if ($e instanceof \Eden\Core\Exception) {
            //set type and level from that
            $trace         = $e->getRawTrace();

            $type         = $e->getType();
            $level         = $e->getLevel();
            $offset     = $e->getTraceOffset();
            $reporter     = $e->getReporter();
        }

        $this->trigger(
            'exception',
            $type,
            $level,
            $reporter,
            $e->getFile(),
            $e->getLine(),
            $message,
            $trace,
            $offset
        );
    }

    /**
     * Returns default handler back to PHP
     *
     * @return this
     */
    public function release()
    {
        restore_exception_handler();
        return $this;
    }

    /**
     * Registers this class' error handler to PHP
     *
     * @return this
     */
    public function register()
    {
        set_exception_handler(array($this, 'handle'));
        return $this;
    }
}
