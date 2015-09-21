<?php //-->
/*
 * This file is part of the Core package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Eden\Handler\test;
 
class Loader extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
		$loader = \Eden\Handler\Loader::i()
			->load('Eden\\Handler\\Error')
			->load('Eden\\Handler\\Exception'); 
			
		$this->assertTrue($loader->handler('Eden\\Core\\Route'));
		$this->assertTrue($loader->handler('Eden\\Handler\\Error'));
		$this->assertFalse($loader->handler('Eden\\Core\\Something'));
    }
	
	public function testGetMeta()
    {
		$meta = \Eden\Handler\Loader::i()
			->getMeta('Eden\\Handler\\Error'); 
		
		$this->assertEquals('Eden\\Handler\\Error', $meta);
    }
}