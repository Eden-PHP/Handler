<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
class EdenHandlerExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle() 
	{
		$test = $this;
		
		Eden\Handler\Exception::i()
			->on('exception', function(
				$type, 
				$level,
            	$class,     
				$errfile,     
				$errline,
            	$errstr
			) use ($test) {
				$test->assertEquals('Test Error', $errstr);
			})
			->handle(new \Exception('Test Error'));
	}

    public function testRelease() 
	{
	}

    public function testRegister() 
	{
	}
}