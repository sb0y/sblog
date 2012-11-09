<?php
class index extends model_base
{
	public static function start()
	{
		self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
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
		$mysqlLimits = array();
		$offset = 0;
		$allCount = self::$db->query ("SELECT COUNT(*) as cnt, c.`contentID`, cc.`contentID` FROM `content` as c, `content_category` as cc WHERE cc.`contentID`=c.`contentID`")->fetch();
		
		$pageCompose = new pagination ($allCount["cnt"]);

		if (isset (self::$get["offset"]))
		{
			$offset = intval (self::$get["offset"]);
			//self::$smarty->clearCache ("main.tpl");
		}

		$pageCompose->readInputData ($offset);
		$mysqlLimits = $pageCompose->calculateOffset();
		$pages = $pageCompose->genPages();
		self::$smarty->assign ("pages", $pages);

		$sqlData = blog::getPosts ($mysqlLimits)->fetchAll();
		
		if ($sqlData)
		{	
			blog::highlightCode ($sqlData, "short");
		}

		return $sqlData;
	}
}
