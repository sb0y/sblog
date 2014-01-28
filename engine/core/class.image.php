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
	private $currentImgInfo = array(), $currentFile = '', $saveOriginal = true, $additionalProcessing = array();
	public $picWidth, $picHeight;
	
	function __construct ($picWidth, $picHeight)
	{
		$this->picWidth = $picWidth;
		$this->picHeight = $picHeight;
	}
	
	function getMime ( $file )
	{
		$this->currentImgInfo["mimeInfo"] = getimagesize ( $file );				
		
		$finfo = new finfo;
		$type = $finfo->file ( $file, FILEINFO_MIME );
		$type = preg_replace ( "/;.*$/", "", $type );
		$this->currentImgInfo["mimeInfo"]["mime"] = $type;

		return $this->currentImgInfo;
	}
	
	function resize ( $file, $postfix = "small" )
	{
		$isOk = true;
		$s = $this->currentImgInfo["mimeInfo"];
		
		$max_x = $this->picWidth;
		$max_y = $this->picHeight;
		
		// return the original file name if not needed to resize
		if ( $s[0] <= $max_x && $s[1] <= $max_y )
			return $file;

		$format = $this->currentImgInfo["format"];
		
		$icfunc = "imagecreatefrom" . $format;

		if ( $format != "jpeg" && $format != "gif" && $format != "png" ) 
			$format = "jpeg";
	
		$outFile = $this->currentImgInfo["file"]."_$postfix.".$format;

		if ( !function_exists ( $icfunc ) )
		{
			return false;
		}

		$source = $icfunc ( $file );

		$ratio_x = 1;
		$ratio_y = 1;

		$ratio_x = $max_x / $s[0];
		$ratio_y = $max_y / $s[1];

		$ratio = min ( $ratio_x, $ratio_y );

		$new_size_x = floor ( $s[0] * $ratio );
		$new_size_y = floor ( $s[1] * $ratio );

		$resource = imagecreatetruecolor ( $new_size_x, $new_size_y );

		imagealphablending ( $resource, false );
		imagesavealpha ( $resource, true );

		if ( !imagecopyresampled ( $resource, $source, 0, 0, 0, 0, $new_size_x, $new_size_y, $s[0], $s[1] ) )
		{
			$isOk = false;
		}

		if ( $format == "gif" && function_exists ( "imagegif" ) )
		{
			imagegif ( $resource, $outFile );

		} else if ( $format == "png" && function_exists ( "imagepng" ) ) {

			imagepng ( $resource, $outFile, 7 );

		} else {

			imagejpeg ( $resource, $outFile, 80 );
		}
						
		imagedestroy ( $resource );
		
		if ( $isOk )
			return $outFile;
		else return false;
	}
	
	function processImage ( $inFile, $outFile = false, $imgID = "" )
	{
		$result = array ( "big"=>NULL, "small"=>NULL );

		if ( $outFile )
		{
			$this->currentImgInfo["file"] = $outFile;
		}

		if ( !$this->currentImgInfo )
			return $result;
		
		$file = $this->currentImgInfo["file"] . '.' . $this->currentImgInfo["format"];

		if ( is_uploaded_file ( $inFile ) )
			move_uploaded_file ( $inFile, $file );
		else rename ( $inFile, $file );
		
		$smallImg = $this->resize ( $file );

		if ( isset ( $this->additionalProcessing ) && isset ( $this->additionalProcessing[$imgID] ) )
		{
			foreach ( $this->additionalProcessing[$imgID] as $k=>$v )
			{
				$this->width = $v["sizes"][0];
				$this->height = $v["sizes"][1];

				$result["customSized"][] = basename ( $this->resize ( $file, "customSized" ) );
			}
		}
		
		$this->currentImgInfo = array();
		$this->additionalProcessing = array();

		$result["small"] = basename ( $smallImg );

		if ( !$this->saveOriginal )
		{
			unlink ( $file );
		} else $result["big"] = basename ( $file );
		
		return $result;
	}
	
	function extendFormat ( $file )
	{
		$this->getMime ( $file );
		$fullFormat = explode ( '/', $this->currentImgInfo["mimeInfo"]["mime"] );
		
		if ( $fullFormat[0] == "image" )
		{
			$this->currentImgInfo["format"] = $fullFormat[1];
			return $fullFormat[1];
		}
		
		return false;
	}

	function setSaveOriginal ( bool $value )
	{
		$this->saveOriginal = $value;
	}
	
	function handleAllUploads ( array &$outNames )
	{
		if (empty ($_FILES))
			return false;
		
		foreach ( $_FILES as $k=>$v )
		{
			if ( !isset ( $outNames[$k] ) || $v["error"] !== 0 )
				continue;
				
			$this->currentImgInfo["file"] =& $outNames[$k];
			
			if ( is_uploaded_file ( $v["tmp_name"] ) )
			{
				if ( $this->extendFormat ( $v["tmp_name"] ) )
				{
					$this->currentImgInfo["file"] = $this->processImage ( $v["tmp_name"], false, $k );
					$outNames[$k] = $this->currentImgInfo["file"];
				}
				
			} else {
				throw new Exception ( "Secure Update Error" ); 
			}
		}
	}

	function additionalProcessing ( $imgID, $width, $height )
	{
		$this->additionalProcessing[$imgID][]["sizes"] = array ( $width, $height );
	}
	
}
