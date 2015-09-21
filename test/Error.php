<?php //-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Eden\Handler\test;
 
class Error extends \PHPUnit_Framework_TestCase
{
    public function testHandle() 
	{
		$test = $this;
		
		\Eden\Handler\Error::i()
			->on('error', function(
				$type, 
				$level,
            	$class,     
				$errfile,     
				$errline,
            	$errstr
			) use ($test) {
				$test->assertEquals('Test Error', $errstr);
				$test->assertEquals('/sample/file.php', $errfile);
				$test->assertEquals(36, $errline);
			})
			->handle(E_NOTICE, 'Test Error', '/sample/file.php', 36);
	}

    public function testRelease()
	{
	}

    public function testRegister() 
	{
	}
	
    public function testSetReporting() 
	{
	}
}