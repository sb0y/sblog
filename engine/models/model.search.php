<?php
class search extends model_base
{
	public static function start()
	{

	}

	public static function searchWithType ( $query, $type = "news" )
	{
		$allCount = $offset = 0;
		$searchFields = array ( "title", "body", "author", "slug" );

		if ( isset ( self::$get [ "offset" ] ) )
			$offset = intval ( self::$get["offset"] );

		$query = preg_replace ( "/[^\w\x7F-\xFF\s]/", " ", $query );
		$query = trim ( preg_replace ( "/\s(\S{1,3})\s/", " ", preg_replace ( "/ +/", "  ", " $query ") ) );
		$query = preg_replace ( "/ +/", " ", $query );

		$preCountFields = array();
		$wordArray = explode ( " ", $query );

		foreach ( $searchFields as $k => $v )
		{
			$searchFields[$k] = "`$v`";
			$preCountFields[$k] = "(";

			for ( $i = 0; count ( $wordArray ) > $i; ++$i )
			{
				$preCountFields[$k] .= " `$v` LIKE '%" . $wordArray[$i] . "%'"; 
				
				end ( $wordArray );

				if ( $i != key ( $wordArray ) )
				{
					$preCountFields[$k] .= " OR"; 
				}
			}

			$preCountFields[$k] .= " )";
		}

		$searchFieldsComp = implode ( ", ", $searchFields );
		$allCount = self::$db->query ( "SELECT DISTINCT `contentID`, $searchFieldsComp FROM `content` WHERE ( " . 
			implode ( " OR ", $preCountFields ) . " )" . ( $type ? " AND `type`='$type' " : '' ) . "GROUP BY `contentID`" )->getNumRows();

		$mysqlLimits = core::pagination ( $allCount, $offset );

		//self::$highlightWords = str_replace (" ", "|", $query);

		$res = self::$db->query ( "SELECT *, co.`contentID`, cc.`catID`, co.`type`, MATCH ($searchFieldsComp) AGAINST ('?') as rel
			FROM `content` as co JOIN `content_category` as cc INNER JOIN `categories` as c ON c.`categoryID`=cc.`catID` 
			AND cc.`contentID`=co.`contentID` WHERE MATCH ($searchFieldsComp) AGAINST ('?' IN BOOLEAN MODE) 
			AND co.`showOnSite`='Y'" . ( $type ? " AND co.`type`='$type' " : '' ) . "LIMIT {$mysqlLimits["start"]},{$mysqlLimits["end"]}", $query, $query );

		$res->runAfterFetchAll[] = array ( "blog", "buildCatsArray" );
		$res->runAfterFetchAll[] = array ( "blog", "arrayUnique" );
		$res->runAfterFetchAll[] = array ( "blog", "makeSlug" );
		//$res->runAfterFetchAll[] = array ( "blog", "highlightBodyForSearch" );

		return $res;
	}
}