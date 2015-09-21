<?php //-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Eden\Handler\test;
 
class Exception extends \PHPUnit_Framework_TestCase
{
    public function testHandle() 
	{
		$test = $this;
		
		\Eden\Handler\Exception::i()
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