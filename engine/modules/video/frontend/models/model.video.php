<?php
class video extends model_base
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

        $sqlData = self::$db->query ("SELECT * FROM `video` $limit");

        //$sqlData->runAfterFetchAll[] = array("blog", "buildCatsArray");
        //$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");

        return $sqlData;
    }

    public static function getPostBySlug($slug)
    {
        $sqlData = self::$db->query ("SELECT * FROM `video` WHERE `slug` = '?'", $slug)->fetch();
        self::$smarty->assign ("fill", $sqlData);
    }
}
