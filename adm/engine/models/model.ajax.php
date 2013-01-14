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
			$uploadedPics = blog::uploadOnePicture ($_POST["slug"], $_POST["pageDir"]);
			$uploadedPics["itemName"] = $_POST["slug"];

			if (!empty ($uploadedPics))
			{
				echo '<script type="text/javascript">parent.pupld.uploadFinished ("'.addslashes(json_encode($uploadedPics)).'")</script>';
			}
		}
	}

	public static function deletePicture()
	{
		if (!isset ($_POST["pictureDelete"]) || 
			!isset ($_POST["picsDir"]) ||
			!isset ($_POST["dirName"]))
		{
			return;
		}

		$dir = CONTENT_PATH."/".$_POST["picsDir"]."/".$_POST["dirName"];

		if (is_dir($dir))
		{
			$dh = opendir ($dir);
			while (false !== ($filename = readdir($dh)))
			{
				if ($filename == '.' || $filename == "..")
					continue;

				$fl = explode ('.', $filename);

				if ($fl[0] == $_POST["pictureDelete"])
				{
					unlink ($dir."/".$filename);

					if (file_exists($dir."/".$fl[0]."_small.jpeg"))
						unlink ($dir."/".$fl[0]."_small.jpeg");
					
					echo "Ok";
					break;
				}
			}

			closedir ($dh);
		}
	}
}
