<?php
class screenshot extends model_base
{
	public static function start()
	{
	}

	public static function generateRandomString ( $length = 100 ) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-+/\|';
		$randomString = '';
		
		for ( $i = 0; $length > $i; ++$i ) 
		{
			$randomString .= $characters [ rand ( 0, strlen ( $characters ) - 1 ) ];
		}

		$res = self::$db->query ( "SELECT COUNT(*) as cnt FROM `tokens` WHERE `token`='?'", $randomString )->fetch();
		
		if ( $res[ "cnt" ] != 0 )
		{
			return self::generateRandomString ( $length );
		}

		return $randomString;
	}

	public static function scaleImage ( $image, $max_x, $max_y )
	{
		$isOk = true;
		$source = imagecreatefrompng ( $image );
		$s = getimagesize ( $image );

		if ( $s [ 0 ] > $max_x && $s [ 1 ] > $max_y )
		{

			$ratio_x = 1;
			$ratio_y = 1;

			$ratio_x = $max_x / $s [ 0 ];
			$ratio_y = $max_y / $s [ 1 ];

			$ratio = min ( $ratio_x, $ratio_y );

			$new_size_x = floor ( $s [ 0 ] * $ratio );
			$new_size_y = floor ( $s [ 1 ] * $ratio );
			
		} else {

			$new_size_x = $s [ 0 ];
			$new_size_y = $s [ 1 ];
		}


		$resource = imagecreatetruecolor ( $new_size_x, $new_size_y );

		imagealphablending ( $resource, false );
		imagesavealpha ( $resource, true );

		if ( !imagecopyresampled ( $resource, $source, 0, 0, 0, 0, $new_size_x, $new_size_y, $s[0], $s[1] ) )
		{
			$isOk = false;
		}

		// Enable output buffering
		ob_start();

		imagepng ( $resource, NULL, 7 );
		// Capture the output
		$imagedata = ob_get_contents();
		// Clear the output buffer
		ob_end_clean();

		imagedestroy ( $resource );
		imagedestroy ( $source );

		return "data:image/png;base64, " . base64_encode (  $imagedata );
	}
}
