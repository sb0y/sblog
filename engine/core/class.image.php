<?php
/*
 * class.image.php
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

class image 
{
	private $currentImgInfo = array(), $currentFile = '';
	public $picWidth, $picHeight;
	
	function __construct ($picWidth, $picHeight)
	{
		$this->picWidth = $picWidth;
		$this->picHeight = $picHeight;
	}
	
	function getMime ($file)
	{
		/*static $finfo;

		if (!is_object ($finfo))
		{
			$finfo = new finfo (FILEINFO_MIME, "/usr/share/misc/magic.mgc"); // return mime type ala mimetype extension
		}
		 	return $finfo->file ($file);
		*/

		$this->currentImgInfo["mimeInfo"] = getimagesize ($file);
						
		return $this->currentImgInfo;

	}
	
	function resize ($file)
	{
		$s = $this->currentImgInfo["mimeInfo"];
		
		$max_x = $this->picWidth;
		$max_y = $this->picHeight;
		
		// return the original file name if not needed to resize
		if ($s [0] <= $max_x && $s [1] <= $max_y)
			return $file;

		$format = $this->currentImgInfo["format"];
		
		if ($this->currentImgInfo["format"]!="jpeg" && $this->currentImgInfo["format"]!="gif") 
				$this->currentImgInfo["format"] = "jpeg";	
		
		$icfunc = "imagecreatefrom" . $format;
		
		$outFile = $this->currentImgInfo["file"]."_small.".$this->currentImgInfo["format"];

		if (!function_exists($icfunc))
		{
			return false;
		}

		$source = $icfunc ($file);

		$ratio_x = 1;
		$ratio_y = 1;

		$ratio_x = $max_x / $s [0];
		$ratio_y = $max_y / $s [1];

		$ratio = min ($ratio_x, $ratio_y);

		$new_size_x = floor ($s [0] * $ratio);
		$new_size_y = floor ($s [1] * $ratio);

		$resource = imagecreatetruecolor ($new_size_x, $new_size_y);

		if (imagecopyresampled ($resource, $source, 0, 0, 0, 0, $new_size_x, $new_size_y, $s [0], $s [1]))
		{
			if ($format == "gif" && function_exists ("imagegif"))
			{
				imagegif ($resource, $outFile);
			} else {
				imagejpeg ($resource, $outFile, 80);
			}
			
			imagedestroy ($resource);
			
			return $outFile;
		} else {
			return null;
		}
		
		return false;
	}
	
	function processImage ($inFile, $outFile=false)
	{
		if ($outFile)
		{
			$this->currentImgInfo["file"] = $outFile;
		}

		if (!$this->currentImgInfo)
			return array ("big"=>"NULL", "small"=>"NULL");
		
		$file = $this->currentImgInfo["file"] . '.' . $this->currentImgInfo["format"];

		if (is_uploaded_file ($inFile))
			move_uploaded_file ($inFile, $file);
		else rename ($inFile, $file);
		
		$smallImg = $this->resize ($file);
		
		$this->currentImgInfo = array();
		
		return array ("big"=>basename ($file), "small"=>basename ($smallImg));
	}
	
	function extendFormat ($file)
	{
		$this->getMime ($file);
		$fullFormat = explode ('/', $this->currentImgInfo["mimeInfo"]["mime"]);
		
		if ($fullFormat[0] == "image")
		{
			$this->currentImgInfo["format"] = $fullFormat[1];
			return $fullFormat[1];
		}
		
		return false;
	}
	
	function handleAllUploads (array &$outNames)
	{
		if (empty ($_FILES))
			return false;
		
		foreach ($_FILES as $k=>$v)
		{
			if (!isset ($outNames[$k]) || $v["error"]!==0)
				continue;
				
			$this->currentImgInfo["file"] =& $outNames[$k];
			
			if (is_uploaded_file ($v["tmp_name"]))
			{
				if ($this->extendFormat ($v["tmp_name"]))
				{
					$this->currentImgInfo["file"] = $this->processImage ($v["tmp_name"]);
					$outNames[$k] = $this->currentImgInfo["file"];
				}
				
			} else {
				throw new Exception ("Secure Update Error"); 
			}
		}
	}
	
}
