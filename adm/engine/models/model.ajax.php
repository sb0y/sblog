<?php
/*
 * model.ajax.php
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

class ajax extends model_base
{
	public static function start()
	{
		
	}
	
	public static function file()
	{
		print_r ($_FILES);
	}

	public static function picture()
	{
		if (!portfolio::initialVerify (true))
		{
			echo '<script type="text/javascript">parent.pupld.uploadError ("'.addslashes(json_encode(system::$errors)).'")</script>';
			return;
		}

		if (isset ($_POST["ajaxFileUpload"]) && isset ($_POST["pageDir"]))
		{
			if ( isset ( $_POST["slug"] ) && $_POST["slug"] )
				$slug = preg_replace ( "/[^a-zа-яё0-9\-\_]+/i", '', $_POST["slug"] );
			else $slug = "";

			if ( isset ( $_POST["pageDir"] ) && $_POST["pageDir"] )
				$pageDir = preg_replace ( "/[^a-z0-9]+/i", '', $_POST["pageDir"] );
			else $pageDir = "";

			$uploadedPics = blog::uploadOnePicture ( $slug, $pageDir );
			$uploadedPics["picUpld"]["itemName"] = $slug;

			if (!empty ($uploadedPics))
			{
				echo '<script type="text/javascript">parent.pupld.uploadFinished ("'.addslashes(json_encode($uploadedPics["picUpld"])).'")</script>';
			}
		}
	}

	public static function checkPost()
	{
		$result = array();

		if (!isset ($_POST["pictureDelete"]) || 
			!isset ($_POST["picsDir"]) ||
			!isset ($_POST["dirName"]))
		{
			return $result;
		}

		if ( isset ( $_POST["picsDir"] ) && $_POST["picsDir"] )
			$result ["picsDir"] = preg_replace ( "/[^a-z0-9\-\_]+/i", '', $_POST["picsDir"] );
		else $result ["picsDir"] = "";

		if ( isset ( $_POST["dirName"] ) && $_POST["dirName"] )
			$result ["dirName"] = preg_replace ( "/[^a-z0-9\-\_]+/i", '', $_POST["dirName"] );
		else $result ["dirName"] = "";

		if ( isset ( $_POST["pictureDelete"] ) && $_POST["pictureDelete"] )
			$result ["pictureDelete"] = preg_replace ( "/[^a-z0-9\-\_]+/i", '', $_POST["pictureDelete"] );
		else $result ["pictureDelete"] = "";

		return $result;
	}

	public static function deletePicture()
	{
		$post = self::checkPost();

		if (!$post)
			return;

		$dir = CONTENT_PATH."/".$post["picsDir"]."/".$post["dirName"];

		if ( is_dir ( $dir ) )
		{
			$dh = opendir ( $dir );
			while ( false !== ( $filename = readdir ( $dh ) ) )
			{
				if ( $filename == '.' || $filename == ".." )
					continue;

				$fl = explode ( '.', $filename );

				if ( $fl[0] == $post["pictureDelete"] )
				{
					unlink ( $dir."/".$filename );

				} else {

					if ( preg_match ( "~({$post["pictureDelete"]}_(?:small|customSized).(?:png|jpeg))~Uuims", $filename, $match ) )
					{
						unlink ( $dir."/".$match[1] );
					}
				}
			}

			closedir ( $dh );
			echo "Ok";
		}
	}

	public static function deletePoster()
	{
		$post = self::checkPost();

		if (!$post)
			return;

		if ( self::$db->updateTable ( "content", array ( "poster"=>"" ), "slug", $post["pictureDelete"] ) );
			self::deletePicture();
	}
}
