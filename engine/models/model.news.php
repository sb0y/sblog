<?php
class news extends model_base
{
	public static function start()
	{
	}
	
	public static function isFavorite ( $slug )
	{
		if ( !isset ( $_SESSION["user"] ) || !$_SESSION["user"] )
			return false;

		$slug = core::generateSlug ( $slug );
		$userID = intval ( $_SESSION["user"]["userID"] );

		$SQLData = self::$db->query ( "SELECT COUNT(*) FROM `favorites` WHERE `slug`='?' AND `userID`=? AND `type`='?'",
		 $slug, $userID, self::$controllerCall )->fetch();
		$SQLData = array_shift ( $SQLData );

		if ( intval ( $SQLData ) )
		{
			return true;
		}

		return false;
	}

	public static function getOnePost ( $get, $dt, $type = "news" )
	{
		if (is_numeric ($get))
			$mode = "contentID";
		else if (is_string ($get))
			$mode = "slug";
		
		$sqlData = self::$db->query ( "SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE c.`$mode`='?' ".
		"AND cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND c.`type`='$type' AND c.`showOnSite`='Y' ".
		"AND cts.`catType`=c.`type` AND c.`dt`=STR_TO_DATE ('?','%d-%m-%Y')", $get, $dt );
		
		$sqlData->runAfterFetch[] = array ( "news", "buildCatsArray" );
		$sqlData->runAfterFetch[] = array ( "news", "makeSlug" );
		$sqlData->runAfterFetchAll[] = array ( "news", "buildCatsArray" );
		$sqlData->runAfterFetchAll[] = array ( "news", "makeSlug" );

		return $sqlData;
	}

	public static function getMainPageArray ( $limits = array() )
	{
		$limit = '';
		//$tpl = array ( "withPic"=>array(), "withoutPic"=>array() );
		$result = array();

		if ( isset ( $limits["start"] ) && isset ( $limits["end"] ) )
			$limit = "LIMIT ".$limits["start"].','.$limits["end"];

		$sqlData = self::$db->query ( "SELECT DISTINCT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
				"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND c.`showOnSite`='Y' AND c.`type`='news' AND EXISTS (".
				"SELECT * FROM `content_category` as cc WHERE cc.`catID`=cts.`categoryID`) GROUP BY c.`contentID` ".
				"ORDER BY c.`dt` DESC $limit" );
		
		$sqlData->runAfterFetchAll[] = array ( "news", "buildCatsArray" );
		$sqlData->runAfterFetchAll[] = array ( "news", "makeSlug" );
		//$sqlData->runAfterFetchAll[] = array ( "news", "buildContentIDArray" );

		if ( $sqlData->getNumRows() <= 0 )
		{
			return array ( "col1" => array() , "col2" => array() );
		}

		$array = $sqlData->fetchAll();

		$leftTop = array_splice ( $array, 0, 3 );
		$rightTop = array_splice ( $array, 0, 5 );

		$all = floor ( count ( $array ) / 2.5 );
		$tmp = array_chunk ( $array, $all );

		if ( isset ( $tmp[0] ) && $tmp[0] )
			$leftTop += $tmp[0];
		
		if ( isset ( $tmp[1] ) && $tmp[1] )
			$rightTop += $tmp[1];

		return array ( "col1" => $leftTop , "col2" => $rightTop );
	}
	
	public static function getPosts ( $limits=array(), $type = "news" )
	{	
		$limit = '';

		if ( isset ( $limits["start"] ) && isset ( $limits["end"] ) )
			$limit = "LIMIT ".$limits["start"].','.$limits["end"];

		$sqlData = self::$db->query ("SELECT DISTINCT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
				"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND c.`showOnSite`='Y' AND c.`type`='$type' AND EXISTS ( ".
				"SELECT * FROM `content_category` as cc WHERE cc.`catID`=cts.`categoryID`) GROUP BY c.`contentID` ORDER BY c.`dt` DESC $limit");
		
		$sqlData->runAfterFetchAll[] = array("news", "buildCatsArray");
		$sqlData->runAfterFetchAll[] = array ("news", "makeSlug");
		//$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");
		
		return $sqlData;
	}
	
	public static function getPostsByCategory ( $categorySlug, $limits = array(), $type = "news" )
	{
		if ( isset ( $limits["start"] ) && isset ( $limits["end"] ) )
			$limit = "LIMIT ".$limits["start"].','.$limits["end"];
		else $limit = "";

		$sqlData = self::$db->query ( "SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
		"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND cts.`catSlug`='?' AND c.`showOnSite`='Y' ".
		"AND c.`type`='$type' ORDER BY c.`dt` DESC $limit", $categorySlug );
		
		$sqlData->runAfterFetchAll[] = array ( "news", "buildCatsArray" );
		$sqlData->runAfterFetchAll[] = array ( "news", "makeSlug" );
		$sqlData->runAfterFetchAll[] = array ( "news", "buildContentIDArray" );
		
		return $sqlData;
	}
	
	public static function getPostsByDate ( $date, $limits = array(), $type = "news" )
	{
		if ( isset ($limits["start"]) && isset ($limits["end"]) )
			$limit = "LIMIT ".$limits["start"].','.$limits["end"];
		else $limit = "";

		$sqlData = self::$db->query ( "SELECT * FROM `content` as c, `content_category` as cc, `categories` as cts WHERE ".
		"cc.`contentID`=c.`contentID` AND cts.`categoryID`=cc.`catID` AND c.`showOnSite`='Y' AND c.`type`='news' AND c.`dt` >= 
		STR_TO_DATE ('?', '%d.%m.%Y') AND c.`type`='$type' ORDER BY c.`dt` ASC $limit", $date );
		
		$sqlData->runAfterFetchAll[] = array("news", "buildCatsArray");
		$sqlData->runAfterFetchAll[] = array("news", "arrayUnique");
		$sqlData->runAfterFetchAll[] = array("news", "makeSlug");
		
		return $sqlData;
	}

	public static function buildContentIDArray ( array &$array )
	{
		if ( !$array )
			return $array;

		$tmp = array();
		foreach ( $array as $k => $v )
		{
			$tmp [ $v["contentID"] ] = $v;
		}

		krsort ( $tmp );

		return $tmp;
	}
	
	public static function buildCatsArray ( array &$array )
	{
		$cats = array();
		$isOneShot = false;

		if ( !isset ( $array[0]["contentID"] ) )
		{
			$isOneShot = true;
			$array = array ( $array );
		}
		
		for ( $i = 0; count ( $array ) > $i; ++$i )
		{
			$id = $array[$i]["contentID"];

			foreach ( $array as $k => $v )
			{
				//echo $v["contentID"]."\n";
				
				if ( $id == $v[ "contentID" ] )
						$cats[ $v["contentID"] ][ $v["catID"] ] = array ( "catName" => $v["catName"], "catSlug" => $v["catSlug"],
						"catID" => $v["catID"] );
			}
			
			//$array[$id]["cats"] = $cats;
			//$cats = array();
		}
	
		//print_r ($cats);
	
		for ( $i = 0; count ( $array ) > $i; ++$i )
		{
			$id = $array[$i]["contentID"];
			$array[$i]["cats"] = $cats[$id];
			unset ( $array[$i]["catID"], $array[$i]["catSlug"], $array[$i]["catType"], $array[$i]["catName"] );
		}

		if ( $isOneShot )
			$array = array_pop ( $array );
						
		return $array;
	}
	
	public static function arrayUnique ( &$array = array() )
	{
		if ( empty ( $array ) )
			return;
				
		foreach ( $array as $k=>$v )
		{
			if (!isset ($tmp[$v["contentID"]]))
			{
				$tmp[$v["contentID"]] = $v;
			}
		}
		
		$array = $tmp;
		
		return $tmp;
	}
	
	public static function showPage ($get)
	{
		$sqlData = self::getOnePost ($get)->fetchAll();
						
		//echo self::$db->buildHtmlLog();
		
		if ($sqlData)
		{			
			system::setParam ("page", "post");
			$sqlData = array_shift ($sqlData);
			self::$smarty->assign ("post", $sqlData);
		
			return $sqlData;
		}
	}

	public static function addComment ( $contentID )
	{
		return comments::add ( $contentID );
	}

	public static function highlightBodyForSearch (&$array=array())
	{
		if (!$array)
			return array();

		if (is_null(self::$highlightWords))
			return $array;

		$words = explode (" ", self::$highlightWords);

		foreach ($words as $k=>$v)
		{
			$words[$k] = '/(?![^<]*<\/.*>)({$v})/';
		}

		foreach ($array as $k=>$v)
		{
			//preg_replace ("/ +/", " ", $query);
			$array[$k]["short"] = preg_replace ($words, "<font color=#cc0000>$0</font>", $v["short"]);
		}

		self::$highlightWords = null;

		return $array;
	}

	public static function makeSlug ( array &$array )
	{
		if ( empty ( $array ) )
			return $array;

		$isOneShot = false;
		$probe = current ( $array );

		if ( !isset ( $probe ["contentID"] ) )
		{
			$isOneShot = true;
			$array = array ( $array );
		}

		foreach ( $array as $k=>$v )
		{
			$array[$k]["URL"] = $v["type"];
			
			if ( isset ( $v["type"] ) && $v["type"] == "news" )
				$array[$k]["URL"] .=  "/" . date ( "d-m-Y", strtotime ( $v["dt"] ) ) . "/";
			else $array[$k]["URL"] .=  "/";
			
			$array[$k]["URL"] .= $v["slug"];
		}

		if ( $isOneShot )
			$array = array_pop ( $array );

		return $array;
	}
	
}
