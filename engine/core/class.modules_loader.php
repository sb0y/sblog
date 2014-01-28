<?php
function engine_autoload_modules ( $className )
{
	$filename = "class.$className.php";

	$paths = array (
		MODULES_PATH . "/" . system::$frontController . "/controllers/$filename",
		MODULES_PATH . "/" . system::$frontController . "/models/model.$className.php"
		//MODULES_PATH . "config." . $class_name . ".php"
	);

	foreach ( $paths as $p )
	{
		if ( file_exists ( $p ) )
		{
			$file = $p;
			break;
		}
	}

	if ( !isset ( $file ) )
	{
		return false;
	}

	include_once ( $file );
}

class modules_loader
{
	const apiVersion = 1;
	public $moduleConfig = array(), $core = null, $activeModules = array();
	public function __construct ( &$core )
	{
		$this->core = $core;
		spl_autoload_register ( "engine_autoload_modules" );
	}

	public function __destruct()
	{

	}

	public function load ( $moduleName )
	{
		// now we have a configuration of module
		$this->moduleConfigLoad ( $moduleName );
		$return = array_shift ( $this->moduleConfig [ "routes" ] );
		// system need to know a origination of this route
		$return [ "isModule" ] = true;
        // set module to active modules list
        $this->activeModules[] = $moduleName;
		return $return;
	}

	public function getInfo()
	{
		return $this->moduleConfig [ "info" ];
	}

	private function moduleConfigLoad ( $moduleName )
	{
		$configPath = MODULES_PATH . "/$moduleName/config.$moduleName.php";
		require ( $configPath );
		$this->moduleConfig = $moduleConfiguration;
	}

	public function getModulesList()
	{
		$dir = MODULES_PATH;
		$root = scandir ( $dir ); 
		
		$result = array();

		foreach ( $root as $value ) 
		{ 
			if ( $value == "." || $value == ".." ) continue; 
			
			if ( is_dir ( "$dir/$value" ) ) 
			{
				include ( "$dir/$value/config.$value.php" );

				if ( $moduleConfiguration [ "info" ] [ "hasAdminInterface" ] )
				{
					unset ( $moduleConfiguration [ "info" ] [ "hasAdminInterface" ] );
					$moduleConfiguration [ "info" ] [ "index" ] = $value;
					$result[] = $moduleConfiguration [ "info" ];
				}
			} 
    	} 

    	return $result;
	}
	public function getModulesListActive()
	{
		$dir = MODULES_PATH;


		$result = array();

		foreach ( $this->activeModules as $value )
		{
            if ( is_dir ( "$dir/$value" ) )
			{
				include ( "$dir/$value/config.$value.php" );

				if ( $moduleConfiguration [ "info" ] [ "hasAdminInterface" ] )
				{
					unset ( $moduleConfiguration [ "info" ] [ "hasAdminInterface" ] );
					$moduleConfiguration [ "info" ] [ "index" ] = $value;
					$result[] = $moduleConfiguration [ "info" ];
				}
			}
    	}
//        echo '<pre>'.print_r($result,1).'</pre>';
    	return $result;
	}

}