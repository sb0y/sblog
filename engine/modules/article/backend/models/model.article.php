<?php
class article extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

	public static function getAllCats ($contentID=false)
	{
		$catHave = "";
		if ($contentID)
		{
			$catHave = ", (SELECT COUNT(*) FROM `content_category` WHERE `contentID`=$contentID AND `catID`=cID) as catSel";
		}

		$sqlData = self::$db->query ("SELECT *, `categoryID` as cID$catHave FROM `categories` WHERE `catType`='article'")->fetchAll();
		self::$smarty->assign ("cats", $sqlData);
	}
}