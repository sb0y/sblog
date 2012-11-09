<?php
abstract class model_base
{
	protected static $smarty, $db, $controllerCall = array(), $args = array(), 
		$routePath = '', $mail = null, $get = array();
	
	abstract static function start();
	
	public static function init ($core)
	{
		foreach ($core->objectInheritance as $property)
		{
			if (property_exists ($core, $property))
			{
				$ref =& $core->$property;
				self::$$property = $ref;
			}
		}
	}	
}
