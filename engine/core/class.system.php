<?php
class system
{
	public static $core = null;
	
	const WARNING = 0;
	const ERROR = 1;
	const INFO = 2;
	const NOTIFY = 3;
	
	const AUTH_REQUIRED = 0x1;
	const ADMIN_ONLY = 0x3;

	public static $chmod = 0;
	
	public static $display = true;
	public static $errors = array();
	public static $wasErrors = false;
	public static $emails = array();
	
	public static function init ($engine="frontend")
	{
		switch ($engine)
		{
			case "frontend":

			//break;
			
			case "backend":
				
			//break;
			default:
				$core = new router;
				self::$core =& $core;
				self::$core->init();
		}
	}
	
	public static function registerEvent (/*$type, $fullName, $txt, $outName=''*/)
	{
		$args = func_get_args();
		$type = array_shift ($args);
				
		switch ($type)
		{
			case "error":
				self::$wasErrors = true;
				$fullName = array_shift ($args);
				self::$errors[$fullName] = array ("fullName"=>$fullName, "txt"=>array_shift ($args), "outName"=>array_shift ($args));
			break;
			case "mail":
				$tmp = array();
				for ($i=0; count($args) > $i; ++$i)
				{
					$tmp[] = $args[$i];
				}
				self::$emails[] = $tmp;
			break;
		}
	}
	
	public static function getEvents ($type, $fields=false)
	{	
		if ($type == "errors")
			$tmpn =& self::$errors;
		
		if (!$fields)
			$array = $tmpn;
		else $array = $fields;	
			
		$tmp = array();
		foreach ($array as $k=>$v)
		{
			if (isset ($tmpn[$k]))
			{
				$tmp[$k] = $v;
			}
		}
		
		return $tmp;
	}
	
	public static function param ($key)
	{
		if (isset (self::$core->params[$key]))
			return self::$core->params[$key];

		return null;
	}

	public static function setParam ($key, $value)
	{
		self::$core->params[$key] = $value;
	}
	
	public static function log ($logLevel, $message)
	{
		
	}
	
	public static function redirect ($url, $delay = false, $txt = false)
	{
		$delay = intval ($delay);
		$url = addslashes ($url);
		
		if ($txt)
		{
			self::$core->smarty->assign ("text", $txt);
			self::$core->smarty->assign ("delay", $delay);
			self::$core->smarty->assign ("url", $url);
			self::setParam ("page", "redirect");
		}
			
		if ($delay)
		{
			$form = 'Refresh: '.$delay.'; URL='.$url;
		} else $form = 'Location: '.$url;
		
		header ($form);
		
		if (!$delay || !$txt)
		{
			self::$display = false;
		}
	}
	
	public static function checkFields ($fields2check)
	{	
		if (!isset ($_POST) || !$fields2check)
			return;
				
		foreach ($_POST as $k=>$v)
		{
			$_POST[$k] = $v = strip_tags ($v, '');
			
			if (array_key_exists ($k, $fields2check))
			{
				if (empty ($v))
					self::registerEvent ("error", $k, "Заполните поле", $fields2check[$k]);
			}
		}
		
		if (empty (self::$errors))
			return true;
			
		return false;
	}
	
	public static function checkErrors()
	{
		return count (self::$errors);
	}

	public static function chmod ($mode)
	{
		self::$chmod = $mode;
	}

	public static function dirIsEmpty ($dirPath)
	{
		$handle = opendir ($dirPath);
		$c = 0;

		while ($file = readdir ($handle) && $c<3) 
		{
			$c++;
		}

		return $c;
	}
}
