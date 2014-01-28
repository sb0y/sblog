<?php
class photo extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

    public static function getPosts ($limits=array())
    {
        $limit = '';

        if (isset ($limits["start"]) && isset ($limits["end"]))
            $limit = "LIMIT ".$limits["start"].','.$limits["end"];

        $sqlData = self::$db->query ("SELECT * FROM `photo` $limit");

        return $sqlData;
    }

    public static function getPostBySlug($slug)
    {
        $sqlData = self::$db->query ("SELECT * FROM `photo` WHERE `slug` = '?'", $slug)->fetch();
        self::$smarty->assign ("fill", $sqlData);
    }

    
}
