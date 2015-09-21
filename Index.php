<?php //-->
/*
 * This file is part of the Eden package.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Handler;

/**
 * Core Factory Class
 *
 * @package    Eden
 * @category   handler
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Index extends Base 
{
	const INSTANCE = 1;
	 
	/**
     * Returns the error class
     *
     * @return Eden\Handler\Error
     */
    public function error() 
	{
        return Error::i();
    }

    /**
     * Returns the exception class
     *
     * @return Eden\Handler\Exception
     */
    public function exception() 
	{
        return Exception::i();
    }

    /**
     * Returns the loader class
     *
     * @return Eden\Handler\Loader
     */
    public function loader() 
	{
        return Eden_Handler_Loader::i();
    }
}