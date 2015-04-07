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
		
		if (!$pageName || !self::$smarty->isCached (self::$smarty->getPageName(), self::$smarty->getCacheID()))
		{
			$cats = self::$db->query ("SELECT * FROM `categories`")->fetchAll();
			self::$smarty->assign ("categories", $cats);
		}
	}

	public static function mainPage()
	{
		$allCount = self::$db->query ( "SELECT COUNT(*) as cnt, c.`contentID`, cc.`contentID` FROM `content` as c, ".
			"`content_category` as cc WHERE cc.`contentID`=c.`contentID` AND c.`showOnSite`='Y'" )->fetch();

		$sqlData = blog::getPosts ( core::pagination ( $allCount [ "cnt" ] ) )->fetchAll();
		
		//if ($sqlData)
		//{	
			//blog::highlightCode ($sqlData, "short");
		//}

		return $sqlData;
	}
}
