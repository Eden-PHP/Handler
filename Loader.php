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
 * Handler for class autoloading. Many developers writing
 * object-oriented applications create one PHP source file per-class
 * definition. One of the biggest annoyances is having to write a
 * long list of needed includes at the beginning of each script
 * (one for each class). When a class is not found an Autoload
 * class is used to define how it is found. Eden now conforms to the
 * PSR-0 standard.
 *
 * @package    Eden
 * @category   core
 * @author     Christian Blanquera cblanquera@openovate.com
 */
class Loader 
{
	protected $_root = array();
	
	protected static $instance = null;
	
	/**
     * One of the hard thing about instantiating classes is
     * that design patterns can impose different ways of
     * instantiating a class. The word "new" is not flexible.
     * Authors of classes should be able to control how a class
     * is instantiated, while leaving others using the class
     * oblivious to it. All in all its one less thing to remember
     * for each class call. By default we instantiate classes with
     * this method.
     *
     * @param [mixed[,mixed..]]
     * @return object
     */
    public static function i() 
	{
        if(is_null(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }
	
	public function __call($name, $args) 
	{
		//if the method name starts with a capital letter
		//most likely they want a class
		//since we are in the loader class
		//we might as well try to load it
		if(preg_match("/^[A-Z]/", $name)) {
			$this->load($name);
		}
		
		return parent::__call($name, $args);
	}
	
	 /**
     * Adds a root and namespace to search in when a class is not
     * already autoloaded
     *
     * @param string|null
     * @param string|null
     * @return this
     */
    public function addRoot($root = null, $namespace = null) 
	{
        if($root === true) {
            $root = realpath(__DIR__.'/../..');
        }

        //turn \eden\core\ to eden\core
        $namespace = ltrim($namespace, '\\');
        //turn eden\core to /Eden/Core
        $namespace = ucwords(str_replace('\\', DIRECTORY_SEPARATOR, $namespace));
        //support for legacy Eden_Class
        $namespace = str_replace('_', DIRECTORY_SEPARATOR, $namespace);

        $this->_root[$root] = $namespace;

        return $this;
    }
	
	/**
     * Determines what meta data we can gather about the class
     * including namespace and file location
     *
     * @param *string the class name
     * @return array
     */
    public function getMeta($class) 
	{
        //With namespacing the full namespace is always passed
        //turn \eden\core\ to eden\core
        $path = ltrim($class, '\\');
        //turn eden\core to /Eden/Core
        $path = ucwords(str_replace('\\', DIRECTORY_SEPARATOR, $path));
        //support for legacy Eden_Class
        $path = str_replace('_', DIRECTORY_SEPARATOR, $path);
		
		$composerPath = false;
		$composerArray = explode('/', $path);
		
		if(count($composerArray) > 1) {
			$composerArray[0] = strtolower($composerArray[0]);
			$composerArray[1] = strtolower($composerArray[1]);
			$composerPath = implode('/', $composerArray);
		}
		
        foreach($this->_root as $root => $namespace) {
            //by default root+path = file
            $file = $root.DIRECTORY_SEPARATOR.$path.'.php';

            //if this is really a file
            if(file_exists($file)) {
                return array($class, $file, str_replace('/', '\\', $namespace));
            }
			
			//try the composer weird naming..
			if($composerPath) {
				$file = $root.DIRECTORY_SEPARATOR.$composerPath.'.php';			
				if(file_exists($file)) {
					return array($class, $file, str_replace('/', '\\', $namespace));
				}
			}
			
            if(!$namespace) {
                continue;
            }

            //lets try root+namespace+path = file
            $file = $root.DIRECTORY_SEPARATOR.$namespace.DIRECTORY_SEPARATOR.$path.'.php';
			
            //if this is really a file
            if(file_exists($file)) {
                return array($class, $file, str_replace('/', '\\', $namespace));
            }
			
			//try the composer weird naming..
			if($composerPath) {
				$file = $root.DIRECTORY_SEPARATOR.$composerPath.DIRECTORY_SEPARATOR.$path.'.php';	
				if(file_exists($file)) {
					return array($class, $file, str_replace('/', '\\', $namespace));
				}
			}
        }

        return array($class, null, null);
    }

    /**
     * Logically includes a class if not included already.
     *
     * @param *string the class name
     * @return bool
     */
    public function handler($class) 
	{
        list($class, $file, $namespace) = $this->getMeta($class);
		
        if($file && require_once($file)) {
            return true;
        }

        return false;
    }

    /**
     * Logically includes a class if not included already.
     *
     * @param *string the class name
     * @return this
     */
    public function load($class) 
	{
        if(!class_exists($class)) {
            $this->handler($class);
        }

        return $this;
    }

    /**
     * Auto registers the auto loader to PHP
     *
     * @return this
     */
    public function register() 
	{
        spl_autoload_register(array($this, 'handler'));
        return $this;
    }
}