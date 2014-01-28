<?php
class plugins_loader
{
	public $path = "", $name = "", $file = "", $content = "";
	public $readyState = false;
	private static $self = null;

	public $smarty, $db, $controllerCall = array(), $args = array(), $get = array();

	// the default constructor for all plugins instances
	// its build all systems objets for you
	public function __construct ( &$smarty = null )
	{
		foreach ( system::$core->objectInheritance as $property )
		{
			if ( property_exists ( system::$core, $property ) )
			{
				if ( $property == "smarty" ) // smarty loads later from internal system plugin
					continue;

				$this->$property =& system::$core->$property;
			}
		}

		$this->smarty =& $smarty;
		self::$self = $this;
	}

	public static function instance()
	{
		return self::$self;
	}

	public static function processPluginLoad ( &$template, $exec, $assign = array() )
	{
		if ( isset ( $assign ) && $assign )
		{
			foreach ( $assign as $k=>$v )
			{
				if ( substr ( $v, strlen ( "is_serialized_string| " ) ) == "is_serialized_string|" )
				{
					$v = str_replace ( "is_serialized_string|", $v, '' );
					$v = unserialize ( $v );	
				}

				$template->assign ( $k, $v );
			}
		}

        $pluginLoader = new plugins_loader ( $template );
        $pluginLoader->init ( $exec );
        $pluginLoader->load();
	}

	public function init ( $pluginName )
	{
		$this->file = "plugin.$pluginName.php";
		$this->path = PLUGIN_PATH . "/{$this->file}";
		$this->name = $pluginName;

		system::$core->smarty->addCacheID ( "PLUGINS|PLUGIN_". $pluginName );

		if ( file_exists ( $this->path ) )
		{
			$this->readyState = true;
			return true;
		} else print ( "Cant find file {$this->path}" );

		return false;
	}

	public function load()
	{
		if ( !$this->readyState )
			return false;

		require_once ( $this->path );
		$class = "plugin_{$this->name}";
		$obj = new $class;
		$obj->smarty =& $this->smarty;

		$obj->run();
	}

	function __destruct()
	{

	}
};
