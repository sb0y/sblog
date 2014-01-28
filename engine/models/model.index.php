<?php
class index extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

	public static function loadCatsMenu()
	{
		$pageName = self::$smarty->getPageName();
		
		if ( !$pageName || !self::$smarty->isCached ( self::$smarty->getPageName(), self::$smarty->getCacheID() ) )
		{
			$cats = self::$db->query ( "SELECT * FROM `categories`" )->fetchAll();
			self::$smarty->assign ("categories", $cats);
		}
	}

	public static function mainPage()
	{
		return news::getMainPageArray();
	}

}
