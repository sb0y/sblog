<?php
class redirect extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

	public static function generateRandomString ( $length = 20 ) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
	    
	    for ( $i = 0; $length > $i; ++$i ) 
	    {
			$randomString .= $characters [ rand ( 0, strlen ( $characters ) - 1) ];
	    }

	    $res = self::$db->query ( "SELECT COUNT(*) as cnt FROM `redirect` WHERE `code`='?'", $randomString )->fetch();
	    
	    if ( $res["cnt"] != 0 )
	    {
	    	return self::generateRandomString ( $length );
	    }

		return $randomString;
	}
}