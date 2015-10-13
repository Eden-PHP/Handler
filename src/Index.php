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
 * Core Factory Class
 *
 * @package  Eden
 * @category Handler
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Index extends Base
{
    /**
     * @const int INSTANCE Flag that designates singleton when using ::i()
     */
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
}
