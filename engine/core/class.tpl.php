<?php
class tpl extends Smarty
{
  const baseTpl = "base.tpl";
  public $tempateDir = "", $compileDir = "", $configDir = "", $cacheDir = "", $pageName = "";
  public $cacheIdShow = null, $runBeforeDisplay = array();

	function __construct ( $configArray )
  {
   		//print_r($configArray);

     	if ( $configArray )
     	{
  	   	foreach ( $configArray as $k=>$v )
  	   	{
  	   		if ( isset ( $this->$k ) )
  	   		{
  	   			$this->$k = $v;
  	   		}
  	   	}
  	  }

      // Class Constructor.
      // These automatically get set with each new instance.

      parent::__construct();

      $this->compile_id = system::param ( "siteDomain" );
      $this->addTemplateDir ( array ( "main" => $this->tempateDir ) );
      $this->setCompileDir ( $this->compileDir );
      $this->setConfigDir ( $this->configDir );
      $this->setCacheDir ( $this->cacheDir );

      if ( system::param ( "optimizeHTML" ) )
        $this->loadFilter ( "pre", "trimwhitespace" );

      $this->setCaching ( Smarty::CACHING_LIFETIME_SAVED );
      $this->setCacheLifetime ( 60 * 60 * 24 );

    	$base_url = rtrim ( dirname ( $_SERVER [ "PHP_SELF" ] ), '/\\' ) . '/';
    	$base_url = "http://" . $this->compile_id . $base_url;
                    
      $this->assign ( "urlBase", $base_url );
      $this->assign ( "siteDomain", $this->compile_id );
   }

   function handleCacheID ( $cacheID )
   {
      return preg_replace ( "/[^0-9a-z\_\-\+\=\*\%\$\|]*/uUmsi", '', core::transliterate ( $cacheID ) );
   }

   function setCacheID ( $cacheID )
   {
      $this->cacheIdShow = $this->handleCacheID ( $cacheID );
   }

   function addCacheID ( $cacheID )
   {
      if ( is_null ( $this->cacheIdShow ) )
      {
        $this->setCacheID ($cacheID);
        return 2;
      }

      $this->cacheIdShow .= '|' . $this->handleCacheID ( $cacheID );

      return 1;
   }

   function getCacheID()
   {
      return $this->cacheIdShow;
   }

   function getPageName()
   {
      return $this->pageName;
   }

   public function clearCurrentCache()
   {
      $tpl = system::param ( "page" );

      if ( $tpl )
      {
        $tpl = "extends:".self::baseTpl."|$tpl.tpl";
      } else $tpl = null;

      $cacheID = $this->getCacheID();

      if ( $cacheID )
        parent::clearCache ( null, $cacheID );
      else parent::clearCache ( $tpl );
   }

   private function eventHandler()
   {
      if ( $this->runBeforeDisplay )
      {
        foreach ( $this->runBeforeDisplay as $k=>$v )
        {
          call_user_func_array ( $v[0] . $v[1] . $v[2], empty ( $v[3] ) ? array() : $v[3] );
        }
      }
   }

   public function renderPage ( $tpl, $cacheID = "", $baseTpl = "" )
   {
      if ( $baseTpl )
      {
        $callStr = "extends:$baseTpl|$tpl";
      } else {
        $callStr = $tpl;
      }

      try {

        //echo $cacheID . "\n";
        if ( $cacheID )
          parent::display ( $callStr, $cacheID );
        else parent::display ( $callStr );

      } catch ( SmartyCompilerException $e ) { 
          // handle compiler errors 
          echo "Error: " . preg_replace ( '!expected one of:.*!', '', $e->getMessage() ); 
      } catch ( SmartyException $e ) { 
          // general Smarty errors 
          echo "Error: " . $e->getMessage(); 
      } catch ( Exception $e ) { 
          // general application errors 
          echo "Error: " . $e->getMessage(); 
      }
   }

   private function displayWrap()
   {
      $this->renderPage ( $this->pageName, $this->cacheIdShow, self::baseTpl );
   }

   public function display ( $page = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL )
   {
      if ( file_exists ( TPL_PATH . "/$page" ) )
  		{
  			if ( $page )
  				$this->pageName = $page;

        $this->eventHandler();
        $this->displayWrap();	
  	  }
  }

  public function moduleDisplay ( $page )
  {
    $this->assign ( "mainTPL", TPL_PATH );

    if ( $page )
      $this->pageName = $page;

    $this->eventHandler();

    $this->addTemplateDir ( array ( "modules" => ( MODULES_PATH . "/" . core::$router->controllerCall . "/" . system::$frontController . "/tpl" ) ) );
    $this->cacheIdShow .= "|MODULES|MODULE_NAME_" . strtoupper ( core::$router->controllerCall );
    $this->displayWrap();
  }

  public function pluginDisplay ( $content )
  {
    return parent::fetch ( "eval: $content" );
  }

  public function isCached ( $template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL )
  {
    if ( $template )
      $tpl = $template;
    else $tpl = system::param ( "page" );

    if ( $cache_id )
      $cacheID = $cache_id;
    else $cacheID = $this->cacheIdShow;

    $checkStr = "extends:".self::baseTpl."|".$tpl.".tpl";

    return parent::isCached ( $checkStr, $cacheID );
  }

  public function clearBrowserCache()
  {
    header ("Expires: -1");
    header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header ("Cache-Control: no-cache");
    header ("Pragma: no-cache");
  }
}
