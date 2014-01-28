<?php
/*
 * model.rss.php
 * 
 * Copyright 2012 ABagrintsev <abagrintsev@topcon.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

class rss extends model_base
{
	public static function start()
	{
		system::$display = false;
		self::setHTTPHeaders();
	}

	public static function setHTTPHeaders()
	{
		header ("Content-Type: text/xml, charset=utf-8");	
		// IE cache fix	
		header ("Cache-Control: no-store, no-cache, must-revalidate");	
		header ("Pragma: no-cache");
	}
	
	public static function getLastPosts()
	{
		$sqlData = self::$db->query ("SELECT *, co.`contentID`, UNIX_TIMESTAMP (`dt`) as tms FROM `content` as co 
		JOIN `content_category` as cc INNER JOIN `categories` as c ON c.`categoryID`=cc.`catID` AND cc.`contentID`=co.`contentID` AND co.`showOnSite`='Y' 
		ORDER BY `dt` DESC");
			
		$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");
		//$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");
		
		return $sqlData;
	}

	public static function getLastPostsWithType ( $type = "news" )
	{
		$sqlData = self::$db->query ("SELECT *, co.`contentID`, UNIX_TIMESTAMP (`dt`) as tms FROM `content` as co 
		JOIN `content_category` as cc INNER JOIN `categories` as c ON c.`categoryID`=cc.`catID` AND cc.`contentID`=co.`contentID` ".
		"AND co.`showOnSite`='Y' AND co.`type`='$type' ORDER BY `dt` DESC");
			
		$sqlData->runAfterFetchAll[] = array ( "blog", "buildCatsArray" );

		return $sqlData;
	}
}
