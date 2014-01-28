<?php
/*
 * model.drafts.php
 * 
 * Copyright 2013 ABagrintsev <abagrintsev@topcon.com>
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

class drafts extends model_base
{
	static $dataType = "news";

	public static function start()
	{
		
	}

	private static function resolveStrType ( $str )
	{
		$str = trim ( $str );

		if ( is_numeric ( $str ) )
			$str = intval ( $str );
		else $str = "'$str'";
	
		return $str;
	}

	private static function assocToSQLStr ( array $array )
	{
		if ( !$array )
			return array();

		$clauseTMP = array();
		foreach ( $array as $k => $v )
		{
			$v = self::resolveStrType ( $v );
			$clauseTMP[] = "`$k`=$v";
		}

		return $clauseTMP;
	}

	public static function save ( $contentID, $userID, $module = "NULL" )
	{
		if ( !isset ( $_POST ) || !$_POST )
			return false;

		if ( isset ( $_POST["savePost"] ) )
			unset ( $_POST["savePost"] );

		$data = $_POST;
		if ( $module != "NULL" )
			$data["type"] = $module;

		$data = serialize ( $data );

		$nick = "";
		if ( isset ( $_POST["nick"] ) && $_POST["nick"] )
			$nick = mb_ereg_replace ( "/[^a-z0-9а-яё\,\.\-\+\"\']/iuU", '', $_POST["nick"] );

		if ( self::$db->query ( "INSERT INTO `content_drafts` (`contentID`,`userID`,`data`,`module`,`draft_nick`) VALUES (?,?,'?','?','?')",
				$contentID, $userID, $data, $module, $nick ) )
			return true;

		return false;
	}

	public static function load ( array $clause = array ( "contentID"=>0 ) )
	{
		if ( !$clause )
			return array();

		$clauseTMP = self::assocToSQLStr ( $clause );
		$data = self::$db->query ( "SELECT * FROM `content_drafts` WHERE " . implode ( " AND ", $clauseTMP ) . " LIMIT 1" );

		if ( !$data->getNumRows() )
			return array();

		$res = $data->fetch();
		$res["data"] = unserialize ( $res["data"] );

		return $res;
	}

	public static function DBSend ( array $data, array $clause, $table = "content" )
	{
		if ( !$data )
			return false;

		$fields = self::$db->query ( "SHOW COLUMNS FROM `$table`" )->fetchAll();
		
		$toAdd = array();
		foreach ( $data as $ak => $av )
		{
			foreach ( $fields as $bk => $bv )
			{
				if ( $ak == $bv["Field"] )
					$toAdd[$ak] = $av;
			}
		}

		if ( !$toAdd )
			return false;

		$updateSet = self::assocToSQLStr ( $toAdd );
		$clauseSet = self::assocToSQLStr ( $clause );

		return self::$db->query ( "UPDATE `$table` SET (".implode(',',$updateSet).") WHERE " . implode ( " AND ", $clauseSet ) );
	}

	public static function getDraftsLists ( $contentID = 0 )
	{
		$result = array();

		$result["article"] = self::$db->query ( "SELECT *, TIMESTAMP (`dt`) as dt FROM `content_drafts` WHERE `contentID`=? AND `userID`=?", 
			$contentID, $_SESSION["user"]["userID"] )->fetchAll();

		$result["all"] = self::$db->query ( "SELECT *, TIMESTAMP (cd.`dt`) as dt FROM `content_drafts` as cd, `content` as c ".
			"WHERE cd.`contentID`=c.`contentID` AND cd.`userID`=?", $_SESSION["user"]["userID"] )->fetchAll();

		return $result;
	}

	public static function setDataType ( $type )
	{
		self::$dataType = $type;
	}

	public static function delete ( $draftID )
	{
		return self::$db->query ( "DELETE FROM `content_drafts` WHERE `draftID`=? AND `module`='?'", $draftID, self::$dataType );
	}

	public static function deleteForUser ( $userID )
	{
		return self::$db->query ( "DELETE FROM `content_drafts` WHERE `userID`=? AND `module`='?'", $userID, self::$dataType );
	}

	public static function deleteByContentID ( $contentID )
	{
		return self::$db->query ( "DELETE FROM `content_drafts` WHERE `contentID`=? AND `module`='?'", $contentID, self::$dataType );
	}

	public static function processDefaultData()
	{
		$contentID = 0;
		$type = "news";

		if ( isset ( $_GET["contentID"] ) )
		{
			$contentID = intval ( $_GET["contentID"] );
		}

		if ( isset ( $_GET["type"] ) )
			$type = preg_replace ( "/[^a-z]/", '', $_GET["type"] );

		return array ( "contentID"=>$contentID, "type"=>$type );
	}
}