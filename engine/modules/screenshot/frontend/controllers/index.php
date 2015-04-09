<?php
/*
 *      index.php
 *
 *      Copyright 2010 Andrei Aleksandovich Bagrintsev <a.bagrintsev@imedia.ru>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
class controller_index extends controller_base 
{
	const image_filter = "/[^0-9a-z\._]/";


	function start()
	{
	}

	function index()
	{
		system::$display = false;
		system::redirect ( "/" );
	}

	function display()
	{
		if (  !isset ( $this->args [ 1 ] ) || ( !isset ( $this->args [ 2 ] ) && !isset ( $this->args [ 3 ] ) ) )
		{
			return system::redirect ( "/" );
		}

		$userID = intval ( $this->args [ 1 ] );
		$big = preg_replace ( self::image_filter, '', $this->args [ 2 ] );
		$small = preg_replace ( self::image_filter, '', $this->args [ 3 ] );

		system::setParam ( "page", "display" );

		if ( !file_exists ( CONTENT_PATH . "/screenshots/$userID/$big" ) )
		{
			return system::redirect ( "/" );
		}

		$dh = opendir ( CONTENT_PATH . "/screenshots/$userID" );
		$filename = "";
		$files = array();

		while ( false !== ( $filename = readdir ( $dh ) ) )
		{
			if ( $filename == "." || $filename == ".." || 
				 $filename == $big || $filename == $small )
			{
				continue;
			}

			if ( preg_match ( "/_small/" , $filename ) )
			{
				continue;
			}

			$stmp = explode ( ".", $filename );
			$small = $stmp[0] . "_small.png";

			if ( !file_exists ( CONTENT_PATH . "/screenshots/$userID/$small" ) )
			{
				$small = "";
			}

			$files[] = array ( 

				"big" => array (
					"url" => system::param ( "urlBase" ) . "content/screenshots/$userID/$filename",
					"dt" => filemtime ( CONTENT_PATH . "/screenshots/$userID/$filename" ),
					"name" => $filename
				),

				"small" => array ( 
					"url" => system::param ( "urlBase" ) . "content/screenshots/$userID/$small", 
					"dt" => filemtime ( CONTENT_PATH . "/screenshots/$userID/$small" ),
					"name" => $small
				)
			);
		}

		$this->smarty->assign ( "img", 
			array ( 
				"big" => array ( 
					"url" => system::param ( "urlBase" ) . "content/screenshots/$userID/$big", 
					"dt" => filemtime ( CONTENT_PATH . "/screenshots/$userID/$big" ),
					"name" => $big
				),
				
				"small" => array ( 
					"url" => system::param ( "urlBase" ) . "content/screenshots/$userID/$small", 
					"dt" => filemtime ( CONTENT_PATH . "/screenshots/$userID/$small" ),
					"name" => $small
				)
			) ); 

		$this->smarty->assign ( "other", $files );
		$this->smarty->assign ( "userID", $userID );
	}

	function upload()
	{
		system::$display = false;
		$response = array();

		if ( !isset ( $_POST [ "token" ] ) && !$_POST [ "token" ] )
		{
			$response [ "error" ] = "Token error";
		}

		$token = preg_replace ( "/[^0-9a-z\_\-+\/\\\|]/i", '', $_POST [ "token" ] );

		if ( !$token )
		{
			$response [ "error" ] = "Token error";
		}

		$mysql = $this->db->query ( "SELECT * FROM `tokens` as t, `users` as u WHERE u.`userID`=t.`userID` AND t.`token`='?' LIMIT 1", $token );

		if ( !$mysql->getNumRows() )
		{
			$response [ "error" ] = "Token error";
		}

		if ( isset ( $response [ "error" ] ) )
		{
			echo json_encode ( $response );
			return;
		}

		$userData = $mysql->fetch();

		if ( isset ( $_FILES [ "screenshot" ] ) && $_FILES [ "screenshot" ] )
		{
			$imageProcessor = new image ( 200, 200 );
			$path = CONTENT_PATH . "/screenshots/" . $userData [ "userID" ];
			system::ensureDirectory ( $path );
			$time = explode ( " ", microtime() );
			$time [ 0 ] = str_replace ( "0." , '', $time [ 0 ] );

			$response [ "screenshot" ] = $path . "/" . implode ( "_", $time );
			$imageProcessor->handleAllUploads ( $response );

		} else {

			$response [ "error" ] = "Where is no `screenshot` field in your multipart request. Nothing to do.";
		}
	
		$response [ "screenshot" ] [ "userID" ] = intval ( $userData [ "userID" ] );

		echo json_encode ( $response );
	}

	function token()
	{
		if ( !isset ( $_SESSION [ "user" ] ) )
		{
			return system::redirect ( "/user/login?to=" . urlencode ( "/screenshot/token" ) ) ;
		}

		system::setParam ( "page", "token" );

		$userID = intval ( $_SESSION [ "user" ] [ "userID" ] );

		$mres = $this->db->query ( "SELECT * FROM `tokens` WHERE `userID`=? LIMIT 1", $userID );
		$res = array();

		if ( $mres->getNumRows() )
		{
			$res = $mres->fetch();
			$token = $res [ "token" ];
		} else {

			$token = screenshot::generateRandomString();
			$this->db->query ( "INSERT INTO `tokens` SET `userID`=?, `token`='?'", $userID, $token );

		}

		$_SESSION [ "user" ] [ "APIs" ] [ "screenshot" ] [ "token" ] = $token;
	}

	function download()
	{
		system::$display = false;

		if ( !isset ( $this->args [ 1 ] ) && !$this->args [ 1 ] &&
		     !isset ( $this->args [ 2 ] ) && !$this->args [ 2 ] )
		{
			return system::redirect ( "/" );
		}

		$userID = intval ( $this->args [ 1 ] );
		$file = preg_replace ( self::image_filter, '', $this->args [ 2 ] );

		if ( !file_exists ( CONTENT_PATH . "/screenshots/$userID/$file" ) )
		{
			return system::redirect ( "/" );
		}

		header ( "Pragma: public" );
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Disposition: attachment; filename=\"$file\"" );

		readfile ( CONTENT_PATH . "/screenshots/$userID/$file" );
	}

	function resize()
	{
		if ( !system::HTTPArg ( 1 ) && 
			 !system::HTTPArg ( 2 ) &&
			 !system::HTTPArg ( 3 ) )
		{
			return system::redirect ( "/" );
		}

		system::$display = false;

		$req = preg_replace ( "/[^0-9x]/", '', system::HTTPArg ( 3 ) );
		$userID = intval ( system::HTTPArg ( 1 ) );

		if ( !$req || !$userID )
		{
			return system::redirect ( "/" );
		}

		$file = preg_replace ( self::image_filter, '', system::HTTPArg ( 2 ) );

		$fp = CONTENT_PATH . "/screenshots/$userID/$file";

		if ( !file_exists ( $fp ) )
		{
			return system::redirect ( "/" );
		}

		$data = explode ( "x", trim ( $req ) );
		$data = array_map ( "trim", $data );

		$image = screenshot::scaleImage ( $fp, $data [ 0 ], $data [ 1 ] );

		echo $image;
	}
	
	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "screenshot" );
	}
}

