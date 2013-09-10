<?php
/*
 * model.portfolio.php
 * 
 * Copyright 2013 Andrei Aleksandovich Bagrintsev <andrei@bagrintsev.me>
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

class portfolio extends model_base
{
	public static function start()
	{
		
	}

	public static function initialVerify ($ignoreExistingPost=false)
	{
		if (!empty ($_POST["slug"]))
		{
			$_POST["slug"] = blog::handlePostName ($_POST["slug"]);

		} else if (!empty ($_POST["title"])) {

			$_POST["slug"] = blog::handlePostName ($_POST["title"]);
		}

		if (!$ignoreExistingPost && self::postExist ("slug", $_POST["slug"]))
		{
			system::registerEvent ("error", "slug", "Такой адрес объекта уже занят", "URL");
		}
		
		if (empty ($_POST["title"]))
		{
			system::registerEvent ("error", "title", "Заголовок не может быть пустым", "Заголовок объекта");
		}

		if (isset ($_FILES["picRealUpload"]) && $_FILES["picRealUpload"]["error"]===0)
		{
			system::registerEvent ("error", "picUpld", "Ошибка при загрузке файла", "Файл картинки");
		}
		
		if (system::checkErrors())
		{
			return false;
		}

		return true;
	}

	public static function addItem ($post)
	{
		if (!self::initialVerify())
		{
			return false;
		}

		unset ($post["savePost"]);

		$content = array();

        foreach ($post as $k=>$v)
        {
			$v = self::$db->escapeString ($v);

			switch ($k) 	
			{
				case "dt":
					$v = "STR_TO_DATE ('$v', '%d-%m-%Y')";
				break;

				case "body":
	 				$v = "'".str_replace ("\n", "<br />", $v)."'";
				break;

				default:
				
					if (!is_numeric($v))
						$v = "'$v'"; 
			}


			$content[$k] = "`$k`=$v";
		}

		self::$db->query ("INSERT INTO `portfolio` SET ".implode (", ", $content));

		return $content;
	}

	public static function postExist ($key, $value)
	{
		$sql = self::$db->query ("SELECT `$key` FROM `portfolio` WHERE `$key`='$value'");

		if ($sql->num_rows > 0)
		{
			return true;
		} 

		return false;
	}
	
	public static function updateItem ($id, $data)
	{
		if (isset ($data["savePost"]))
			unset ($data["savePost"]);
				
		if (!isset ($data["showOnSite"]))
		{
			$data["showOnSite"] = 'N';
		}

		if (!empty ($data["slug"]))
		{
			$data["slug"] = self::handlePostName ($data["slug"]);

		} else if (!empty ($data["title"])) {

			$data["slug"] = self::handlePostName ($data["title"]);
		}

		//self::$db->updateTable ("content", $data, "contentID", $id);
				
		self::$db->query ("UPDATE `portfolio` SET `dt`=STR_TO_DATE ('?', '%d-%m-%Y'), `title`='?', slug='?', `body`='?', `short`='?',
			`showOnSite`='?' WHERE `contentID`=?", $data["dt"], $data["title"], $data["slug"], $data["body"], $data["short"], 
			$data["showOnSite"], $id);
	}
}
