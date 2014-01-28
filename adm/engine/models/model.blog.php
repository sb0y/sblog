<?php
/*
 * model.blog.php
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

class blog extends model_base
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
		if (self::postExist ("slug", $post["slug"]))
		{
			system::registerEvent ("error", "slug", "Такой адрес поста уже занят", "URL");
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
		self::$db->query ( "INSERT INTO `content` SET " . implode ( ", ", $content ) );
		$id = self::$db->insert_id();
		self::handleCats ($cats, $id);
		self::$smarty->clearCache (null, "MAINPAGE|SEARCH_RES|BLOG|CATSELECT|RSS");
		self::$smarty->clearCache (null, "MAINPAGE");
		
		return $content;
	}

	public static function handlePostName ($str)
	{
		return core::generateSlug ( $str );
	}

	public static function buildList ( $target, $type="", array $clause=array() )
	{
		if ( $type )
			if ( $target == "categories" ) // временный костыль
				$clause[] = "`catType`='$type'";
			else $clause[] = "`type`='$type'";

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
						self::$smarty->clearCache (null, "MAINPAGE|SEARCH_RES|BLOG|CATSELECT|RSS");
					}
				break;
			}
		}

		if (isset ($_POST["groupDelete"]) && !empty ($_POST["rows"]))
			self::$db->query ("DELETE FROM `$target` WHERE `".$columns[0]["Field"]."` IN (".
							implode (",",$_POST["rows"]).")");

		$mysqlLimits = array();
		$offset = 0;
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
			{$mysqlLimits["start"]},{$mysqlLimits["end"]}")->fetchAll();	
		
		self::$smarty->assign ("list", $sqlData);

		return $sqlData;
	}

	public static function buildForm ($target)
	{
		$id = array_keys ($_GET);
		$keyName = $id [ count ($_GET) - 1 ];
		$id = intval ( $_GET [ $keyName ] );
		
		$sqlData = self::$db->query ("SELECT * FROM `$target` WHERE `$keyName`=$id LIMIT 1")->fetch();

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

		self::$smarty->clearCache ( null, "MAINPAGE|SEARCH_RES|BLOG|CATSELECT|RSS|{$data["slug"]}" );
		self::$smarty->clearCache ( null, "MAINPAGE" );
		self::$smarty->clearCache ( null, $data["slug"] );

	}

	public static function updateComment ( $commentID )
	{
		self::$db->updateTable ("comments", $_POST, "commentID", $commentID);
		$contentID = intval ($_GET["contentID"]);
		system::redirect (system::param ("urlBase")."showPostComments/$contentID");
	}

	public static function saveDraft()
	{
		self::$db->query ( "INSERT INTO `drafts` SET `contentID`=0, `userID`=?, `title`='?', `slug`='?', `body`='?', ".
			"`dt`=STR_TO_DATE ('?', '%d-%m-%Y'), `draft_add_date`=NOW()", 
			$_SESSION["user"]["userID"], $_POST["title"], $_POST["slug"], $_POST["body"], $_POST["dt"] );
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
			//blog::saveDraft();

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

	public static function loadDraft ( $call )
	{
		if (is_numeric ($call))
		{
			$clause = "`id`=?";
		} else {
			$clause = "`slug`='?'";
		}
		
		//echo $call;

		$fill = self::$db->query ("SELECT *, DATE_FORMAT (`dt`, 'd%-%m-%Y') as dt FROM `drafts` WHERE $clause ORDER BY `id` DESC LIMIT 1", $call)->fetch();

		return $fill;
	}

	public static function postExist ($key, $value)
	{
		$sql = self::$db->query ("SELECT `$key` FROM `content` WHERE `$key`='$value'");

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
}
