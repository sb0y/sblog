<?php
class blog extends model_base
{
	public static $htmlParser = null;
	
	public static function start()
	{
		if (is_null(self::$htmlParser))
		{
			self::$htmlParser = new highlight_code;
		}
	}
	
	public static function getOnePost ($get)
	{
		if (is_numeric ($get))
			$mode = "contentID";
		else if (is_string ($get))
			$mode = "url_name";
				
		$sqlData = self::$db->query ("SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE c.`$mode`='?' ".
		"AND cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID`", $get);
		
		$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");

		return $sqlData;
	}
	
	public static function getPosts ($limits=array())
	{	
		$limit = '';

		if (isset ($limits["start"]) && isset ($limits["end"]))
			$limit = "LIMIT ".$limits["start"].','.$limits["end"];

		$sqlData = self::$db->query ("SELECT DISTINCT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
                "cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND EXISTS (
                SELECT * FROM `content_category` as cc WHERE cc.`catID`=cts.`categoryID`) GROUP BY c.`contentID` ORDER BY c.`dt` DESC $limit");
		
		$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");
		//$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");
		
		return $sqlData;
	}
	
	public static function getPostsByCategory ($categorySlug)
	{
		$sqlData = self::$db->query ("SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
		"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND cts.`catSlug`='?' ORDER BY c.`dt` DESC", $categorySlug);
		
		$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");
		
		return $sqlData;
	}
	
	public static function getPostsByDate ($date)
	{		
		$sqlData = self::$db->query ("SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
		"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND c.`dt` >= '?' ORDER BY c.`dt` ASC", $date);
		
		$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");
		$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");
		
		return $sqlData;
	}
	
	public static function buildCatsArray (&$array)
	{
		$cats = array();
		
		for ($i=0; count($array) > $i; ++$i)
		{	
			$id = $array[$i]["contentID"];

			foreach ($array as $k=>$v)
			{
				//echo $v["contentID"]."\n";
				
				if ($id == $v["contentID"])
						$cats[$v["contentID"]][$v["catID"]] = array ("catName"=>$v["catName"], 	
									"catSlug"=>$v["catSlug"]);
			}
			
			//$array[$id]["cats"] = $cats;
			//$cats = array();
		}
	
		//print_r ($cats);
	
		for ($i=0; count($array) > $i; ++$i)
		{
			$id = $array[$i]["contentID"];
			$array[$i]["cats"] = $cats[$id];
		}
						
		return $array;
	}
	
	public static function arrayUnique (&$array=array())
	{
		if (empty ($array))
			return;
				
		foreach ($array as $k=>$v)
		{
			if (!isset ($tmp[$v["contentID"]]))
			{
				$tmp[$v["contentID"]] = $v;
			}
		}
		
		$array = $tmp;
		
		return $tmp;
	}
	
	public static function highlightCode (&$data, $key="body")
	{
		return self::$htmlParser->highlightCode ($data, $key);
	}
	
	public static function showPage ($get)
	{
		$sqlData = self::getOnePost ($get)->fetchAll();
						
		//echo self::$db->buildHtmlLog();
		
		if ($sqlData)
		{			
			system::setParam ("page", "post");
			
			$sqlData = array_shift ($sqlData);
			
			self::highlightCode ($sqlData["body"]);		
			self::$smarty->assign ("post", $sqlData);
		
			return $sqlData;
		}
	}
	
	public static function search ($query)
	{
		$query = preg_replace ("/[^\w\x7F-\xFF\s]/", " ", $query);
		$query = trim (preg_replace ("/\s(\S{1,3})\s/", " ", preg_replace ("/ +/", "  ", " $query ")));
		$query = preg_replace ("/ +/", " ", $query);

		$res = self::$db->query ("SELECT *, co.`contentID`, cc.`catID`, MATCH (`title`,`body`,`author`) AGAINST ('?') as rel
			FROM `content` as co JOIN `content_category` as cc INNER JOIN `categories` as c ON c.`categoryID`=cc.`catID` AND cc.`contentID`=co.`contentID`
			WHERE MATCH (`title`,`body`,`author`) AGAINST ('?') > 0", $query, $query);

		$res->runAfterFetchAll[] = array("blog", "buildCatsArray");
		$res->runAfterFetchAll[] = array("blog", "arrayUnique");

		return $res;
	}


	public static function addComment ($contentID)
	{
		//echo $_POST["comment"];

		if (!isset ($_SESSION["user"]))
			return false;

		$comment = comments::ex_strip_tags ($_POST["comment"]);
		$comment = trim (comments::bbcodes ($comment));
		$insip = isset ($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:'';

		if (!$comment)
			return false;

		self::$db->query ("INSERT `comments` SET `contentID`=?, `userID`=?, `dt`=NOW(), `email`='?', `author`='?', `body`='?', `guest`='N' , `ip`=INET_ATON('?')", 
			$contentID, $_SESSION["user"]["userID"], $_SESSION["user"]["email"], $_SESSION["user"]["nick"], $comment, $insip);

		$commentID = self::$db->insert_id();

		self::$db->query ("UPDATE `content` set `comments_count`=`comments_count`+1 WHERE `contentID`=?", $contentID);
		self::$smarty->clearCurrentCache();
		system::redirect (self::$routePath."#comment_$commentID");
	}
	
}
