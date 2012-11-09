<?php
class tpl extends Smarty
{
	public $tempateDir = "", $compileDir = "", $configDir = "", $cacheDir = "", $pageName = "";
  public $cacheIdShow = null, $runBeforeDisplay = array();

	function __construct ($configArray)
  {
   		//print_r($configArray);

     	if ($configArray)
     	{
  	   	foreach ($configArray as $k=>$v)
  	   	{
  	   		if (isset ($this->$k))
  	   		{
  	   			$this->$k = $v;
  	   		}
  	   	}
  	  }

      // Class Constructor.
      // These automatically get set with each new instance.

      parent::__construct();

      $this->setTemplateDir ($this->tempateDir);
      $this->setCompileDir ($this->compileDir);
      $this->setConfigDir ($this->configDir);
      $this->setCacheDir ($this->cacheDir);

      $this->setCaching (Smarty::CACHING_LIFETIME_CURRENT);
      $this->setCacheLifetime (3600);
        
    	$base_url = rtrim (dirname ($_SERVER["PHP_SELF"]), '/\\').'/';
    	$base_url = "http://" . system::param("siteDomain") . $base_url;
                    
      $this->assign ("urlBase", $base_url);
   }

   function setCacheID ($cacheID)
   {
      $this->cacheIdShow = $cacheID;
   }

   function addCacheID ($cacheID)
   {
      if (is_null($this->cacheIdShow))
      {
        $this->setCacheID ($cacheID);
        return 2;
      }

      $this->cacheIdShow .= '|'.$cacheID;

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

   function clearCurrentCache()
   {
      $this->clearCache ((TPL_PATH.'/'.system::param("page").".tpl"), $this->getCacheID());
   }

   function display ($page)
   {
		if (file_exists (TPL_PATH."/$page"))
		{
			if ($page)
				$this->pageName = $page;

			if ($this->runBeforeDisplay)
			{
				foreach ($this->runBeforeDisplay as $k=>$v)
				{
					call_user_func_array ($v[0].$v[1].$v[2], empty ($v[3])?array():$v[3]);
				}
			}

			if (is_null ($this->cacheIdShow))
				parent::display ($this->pageName);
			else parent::display ($this->pageName, $this->cacheIdShow);
		}
	}
}
