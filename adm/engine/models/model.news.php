<?php
/*
 * model.news.php
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

class news extends model_base
{
	public static function start ()
	{
		
	}
	
	public static function checkPostErrors ( $post )
	{	
		if (empty ($post["title"]))
		{
			system::registerEvent ("error", "title", "Заголовок не может быть пустым", "Заголовок поста");
		}

		if (empty ($post["categories"]))
		{
			system::registerEvent ("error", "categories", "Необходимо выбрать категорию(ии)", "Категории");
		}

		if (empty ($post["short"]))
		{
			system::registerEvent ("error", "short", "У новости должен быть анонос текста", "Анонс текста");
		}

		if ( isset ( $post["short"] ) && mb_strlen ( $post["short"] ) > 140 )
		{
			system::registerEvent ("error", "short", "Количество символов в анонсе текста превышет 140 символов.", "Анонс текста слишком длинный");
		}

		if (system::checkErrors())
		{
			return false;
		}

		return true;
	}

	public static function writePost ( $post, $type = "news" )
	{
		if ( self::postExist ("slug", $post["slug"], $post["dt"] ) )
		{
			system::registerEvent ("error", "slug", "Такой адрес поста уже занят для текущей даты", "URL");
		}

		if ( !self::checkPostErrors ( $post ) )
		{
			return false;
		}

		unset ($post["savePost"]);

		$post["author"] = $_SESSION["user"]["nick"];
		$post["userID"] = $_SESSION["user"]["userID"];
		$post["type"] = $type;

		if (isset ($post["catName"]))
		{
			if (!empty($post["catName"]))
				self::addNewCat();

			unset ($post["catName"]);
			unset ($post["catSlug"]);
		}
		
		$cats = array();
		if (isset ($post["categories"]))
		{
			$cats = $post["categories"];
			unset ($post["categories"]);
		}
		
		$content = array();

        foreach ($post as $k=>$v)
        {
			$v = self::$db->escapeString ($v);

			switch ($k) 	
			{
				case "dt":
					$v = "STR_TO_DATE ('$v', '%d-%m-%Y')";
				break;

				case "short":
				case "body":
	 				$v = "'".str_replace ("\n", "<br />", $v)."'";
				break;

				default:
				
					if (!is_numeric($v))
						$v = "'$v'"; 
			}

			$content[$k] = "`$k`=$v";
		}

		$short = preg_split ( "/(?:&lt;|<)!--\s*more\s*--(?:&gt;|>)+/i", $post["body"] );

		if ( is_array ( $short ) && count ( $short ) > 1 )
		{
			$content["short"] = "`short`='" . self::$db->escapeString ( nl2br ( array_shift ( $short ) ) ) . "'";
		}

		//print_r ($_POST);
		//echo "INSERT INTO `content` SET ".implode (", ", $content);
		
		if ( self::$db->query ( "INSERT INTO `content` SET " . implode ( ", ", $content ) ) )
		{
			$content["contentID"] = self::$db->insert_id();
			self::handleCats ( $cats, $content["contentID"] );
			self::clearCommonCache();
			return $content;
		}

		return false;
	}

	public static function handlePostName ($str)
	{
		return core::generateSlug ( $str );
	}

	public static function buildList ( $target, $type="", array $clause = array() )
	{
		$clauseSTR = "";

		if ( $clause )
			$clauseSTR = implode ( " AND ", $clause );

		if ( $clauseSTR )
			$clauseSTR = "WHERE $clauseSTR";

		$columns = self::$db->query ("SHOW COLUMNS FROM `$target`")->fetchAll();

		if (isset ($_GET["action"]))
		{
			switch ($_GET["action"])
			{
				case "delete":
					$id = array_keys ($_GET);
					$keyName = $id [count ($_GET)-1];
					$id = intval ($_GET [$keyName]);

					self::$db->query ("DELETE FROM `$target` WHERE `?`=?", $keyName, $id);

					if ( $target == "content" )
					{
						self::$db->query ("DELETE FROM `content_category` WHERE `id`=?", $id);
					}

					self::$smarty->clearAllCache();
				break;
			}
		}

		if (isset ($_POST["groupDelete"]) && !empty ($_POST["rows"]))
			self::$db->query ("DELETE FROM `$target` WHERE `".$columns[0]["Field"]."` IN (".
							implode (",",$_POST["rows"]).")");

		$mysqlLimits = array();
		$offset = 1;
		$allCount = self::$db->query ("SELECT COUNT(*) as cnt FROM `$target` $clauseSTR")->fetch();
        $pageCompose = new pagination ($allCount["cnt"]);
		$sort = $columns[0]["Field"];
		$direction = "DESC";

		if (!empty (self::$get["offset"]))
		{
			$offset = intval (self::$get["offset"]);
		}

		if (isset ($_GET["direction"]) && $_GET["direction"])
		{
			if ($_GET["direction"] == "DESC")
				$direction = "ASC";
			else if ( $_GET["direction"] == "ASC" )
				$direction = "DESC";
		}

		if ( isset ( $_GET["sort"] ) && $_GET["sort"] )
		{
			$sort = $_GET["sort"];
		}

		$pageCompose->readInputData ( $offset, 20 );
		$mysqlLimits = $pageCompose->calculateOffset();
		$pages = $pageCompose->genPages();
		self::$smarty->assign ("pages", $pages);
		self::$smarty->assign ("direction", $direction);
		self::$smarty->assign ("sort", $sort);
		self::$smarty->assign ("allCount", $allCount["cnt"]);

		$sqlData = self::$db->query ("SELECT * FROM `$target` $clauseSTR ORDER BY `$sort` $direction LIMIT 
			{$mysqlLimits["start"]},{$mysqlLimits["end"]}");

		if ( $target != "categories" )
			$sqlData->runAfterFetchAll[] = array ("news", "makeSlug");
		
		$res = $sqlData->fetchAll();
		
		self::$smarty->assign ( "list", $res );

		return $res;
	}

	private static function updateKey ( $key,$contentID )
	{
		self::$db->query ( "UPDATE `content` SET `key`='?' WHERE `contentID`=?", $key, $contentID );	
		
	}

	public static function buildForm ($target)
	{
		$id = array_keys ($_GET);
		$keyName = $id [ count ($_GET) - 1 ];
		$id = intval ( $_GET [ $keyName ] );
		
		$sqlData = self::$db->query ("SELECT * FROM `$target` WHERE `$keyName`=$id LIMIT 1")->fetch();
		
		if ( isset ( $sqlData["key"] ) && !$sqlData["key"] )
		{
			$sqlData['key'] = core::generateKey();
			self::updateKey($sqlData['key'],$sqlData['contentID']);
		}
		
		self::$smarty->assign ("fill", $sqlData);
		
		return $sqlData;
	}

	public static function getAllCats ($contentID=false)
	{
		$catHave = "";
		if ($contentID)
		{
			$catHave = ", (SELECT COUNT(*) FROM `content_category` WHERE `contentID`=$contentID AND `catID`=cID) as catSel";
		}

		$sqlData = self::$db->query ("SELECT *, `categoryID` as cID$catHave FROM `categories` WHERE `catType`='news'")->fetchAll();
		self::$smarty->assign ("cats", $sqlData);
	}

	public static function addNewCat ( $type = "news" )
	{
		system::$display = false;
		
		if ( isset ( $_POST["catName"] ) )
		{
			$catName = htmlspecialchars ( $_POST["catName"] );

			if ( isset ( $_POST["catSlug"] ) && $_POST["catSlug"] )
			{
				$catSlug = core::generateSlug ( $_POST["catSlug"] );
			} else {
				$catSlug = core::generateSlug ( $_POST["catName"] );
			}

			self::$db->query ( "INSERT INTO `categories` SET `catName`='?', catSlug='?', catType='?'", 
				$catName, $catSlug, $type );

			return self::$db->insert_id();
		}

		return false;
	}
	
	public static function handleCats (&$data, $id)
	{
		if (!empty ($data))
		{
			self::$db->query ("DELETE FROM `content_category` WHERE `contentID`=?", $id);
			
			$catStr = array();
			foreach ($data as $k=>$v)
			{
				$catStr[] = "('', $id, $v)";
			}

			self::$db->query ("INSERT INTO `content_category` VALUES ".implode(", ", $catStr));
		}
		
		unset ($data);
	}

	public static function updatePost ($id, $data)
	{
		if ( !blog::postExist ( "contentID", $id ) )
		{
			system::registerEvent ("error", "title", "Новости, которую вы редактируете не существует.", "URL");
		}

		if ( !self::checkPostErrors ( $data ) )
		{
			return false;
		}

        if ( isset ($data["savePost"]) )
			unset ($data["savePost"]);
			
		if ( isset ($data["picWidth"]) )
			unset ($data["picWidth"]);
			
		if ( isset ($data["picHeigth"]) )
			unset ($data["picHeigth"]);
		
		self::handleCats ( $data["categories"], $id );

		if ( !isset ($data["showOnSite"]) )
		{
			$data["showOnSite"] = 'N';
			self::$smarty->clearAllCache();
		}
		
		if ( empty ( $data["short"] ) )
		{
			$data["short"] = preg_split ( "/(?:&lt;|<)!--\s*more\s*--(?:&gt;|>)+/i", $data["body"] );

			if ( is_array ( $data["short"] ) )
			{
				$data["short"] = array_shift ( $data["short"] );
			}

		} else {
			$data["short"] = nl2br ( $data["short"] );
		}

		if (isset ($data["catName"]))
			unset ($data["catName"]);

		if (isset ($data["catSlug"]))
			unset ($data["catSlug"]);

		if (!empty ($data["slug"]))
		{
			$data["slug"] = core::generateSlug ( $data["slug"] );

		} else if ( !empty ($data["title"] ) ) {

			$data["slug"] = core::generateSlug ( $data["title"] );
		}

		//self::$db->updateTable ("content", $data, "contentID", $id);

		$date = date ( "d-m-Y", strtotime ( $data["dt"] ) );
		self::$smarty->clearCache ( null, "{$date}_newsdate|{$data["slug"]}" );
		self::clearCommonCache();

		if ( $data["poster"] )
		{
			return self::$db->query ( "UPDATE `content` SET `dt`=STR_TO_DATE ('?', '%d-%m-%Y'), `title`='?', slug='?', `body`='?', `short`='?',
				`showOnSite`='?', `editedByID`=?, `editedByNick`='?', `editedOn`=NOW(), `poster`='?' WHERE `contentID`=?", 
				$data["dt"], $data["title"], $data["slug"], $data["body"], $data["short"], $data["showOnSite"], 
				$_SESSION["user"]["userID"], $_SESSION["user"]["nick"], $data["poster"], $id );
		} else {
			return self::$db->query ( "UPDATE `content` SET `dt`=STR_TO_DATE ('?', '%d-%m-%Y'), `title`='?', slug='?', `body`='?', `short`='?',
				`showOnSite`='?', `editedByID`=?, `editedByNick`='?', `editedOn`=NOW() WHERE `contentID`=?", 
				$data["dt"], $data["title"], $data["slug"], $data["body"], $data["short"], $data["showOnSite"], 
				$_SESSION["user"]["userID"], $_SESSION["user"]["nick"], $id );
		}
	}

	public static function updateComment ( $commentID, $data, $contentID = 0 )
	{
		if ( self::$db->updateTable ( "comments", $data, "commentID", $commentID ) )
		{
			if ( $contentID )
			{
				$content = self::$db->query ( "SELECT DATE_FORMAT (`dt`,'%d-%m-%Y') as date,`slug` FROM `content` WHERE ".
					"`contentID`=? LIMIT 1", $contentID );

				if ( $content->getNumRows() )
				{
					$res = $content->fetch();
					self::$smarty->clearCache ( null, "{$res["date"]}_newsdate|{$res["slug"]}" );
				}

				system::redirect ( "/adm/news/showPostComments/$contentID" );
			}
		}
	}

	public static function uploadOnePicture ($postName, $postDir="postImages")
	{
		if ( !empty ( $_POST["picWidth"] ) )
			$width = intval ( $_POST["picWidth"] );
		else $width = 200;

		if (! empty ( $_POST["picHeight"] ) )
			$heigth = intval ( $_POST["picHeight"] );
		else $heigth = 200;

		$path = CONTENT_PATH."/$postDir/$postName/";

		if ( !is_dir ( $path ) || system::dirIsEmpty ( $path ) )
		{
			if ( !is_dir ( $path ) )
				mkdir ( $path, 0777, true );
		}

		$imageProcessor = new image ( $width, $heigth );

		if ( $postDir == "posterImages" )
		{
			$imageProcessor->additionalProcessing ( "poster", 640, 0 );
		}

		$expectedPics = array ( "picUpld" => $path.time(), "poster" => $path.$postName );
        $imageProcessor->handleAllUploads ( $expectedPics );

        return $expectedPics;
	}

	public static function postExist ( $key, $value, $dt )
	{
		$sql = self::$db->query ("SELECT `$key` FROM `content` WHERE `$key`='$value' AND `dt`=STR_TO_DATE ('$dt', '%d-%m-%Y')");

		if ($sql->num_rows > 0)
		{
			return true;
		} 

		return false;
	}

	public static function updateCategory ($categoryID, $data)
	{
		self::$db->updateTable ("categories", $data, "categoryID", $categoryID);
		system::redirect (system::param ("urlBase")."categories");
	}

	public static function addCat ( $data, $type = "news" )
	{
		if ( !isset ( $data["catSlug"] ) || !$data["catSlug"] )
		{
			$data["catSlug"] = core::generateSlug ( $data["catName"] );
		}

		return self::$db->query ("INSERT INTO `categories` SET `catName`='?', `catSlug`='?', `catType`='$type'", 
				$data["catName"], $data["catSlug"]);
	}
	
	public static function showAttachedPics ($fill, $picsDir="postImages")
	{
		$picFiles = array();

		if (!empty ($fill["slug"]))
		{
			$dir = CONTENT_PATH."/$picsDir/".$fill["slug"];

			if (is_dir ($dir))
			{
				$dh = opendir ($dir);
				$deleteFile = false;

				while (false !== ($filename = readdir($dh)))
				{
					if ($filename == '.' || $filename == "..")
						continue;

					$fl = explode ('.', $filename);

					if (preg_match ("/(.*)_small.*/", $fl[0], $matches))
					{
						$picFiles[$matches[1]]["small"] = $filename;
					} else {
						$picFiles[$fl[0]]["big"] = $filename;
					}
				}

				closedir ($dh);

				if (isset ($_GET["delPic"]))
				{
					foreach ($picFiles as $k=>$v)
					{
						if ($k == $_GET["delPic"])
						{
							unlink ($dir.'/'.$v["big"]);

							if (file_exists ($dir.'/'.$v["small"]))
							{
								unlink ($dir.'/'.$v["small"]);
							}

							unset ($picFiles[$k]);
						}
					}
				}
			}
		}

		self::$smarty->assign ("picFiles", $picFiles);
	}

	public static function makeSlug ( array &$array )
	{
		if ( empty ( $array ) )
			return $array;

		$isOneShot = false;

		if ( !isset ( $array[0]["contentID"] ) )
		{
			$isOneShot = true;
			$array = array ( $array );
		}

		foreach ( $array as $k=>$v )
		{
			if ( !isset ( $v["slug"] ) )
				return $array;

			$array[$k]["URL"] = date ( "d-m-Y", strtotime ( $v["dt"] ) ) . "/" . $v["slug"];
		}

		if ( $isOneShot )
			$array = array_pop ( $array );

		return $array;
	}

	public static function clearCommonCache()
	{
		self::$smarty->clearCache ( null, "MAINPAGE" );
		self::$smarty->clearCache ( null, "CATSELECT" );
		self::$smarty->clearCache ( null, "SEARCH_RES" );
		self::$smarty->clearCache ( null, "RSS|MAINPAGE|SEARCH_RES|CATSELECT|DTSELECT" );
	}
}
