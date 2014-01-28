<?php
class article extends model_base
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

        $sqlData = self::$db->query ("SELECT * FROM `content` WHERE `type`='article' $limit");

        $sqlData->runAfterFetchAll[] = array ("news", "buildCatsArray");
        $sqlData->runAfterFetchAll[] = array ("news", "makeSlug");

        //$sqlData->runAfterFetchAll[] = array("blog", "arrayUnique");

        return $sqlData;
    }

    public static function getPostBySlug ( $slug )
    {
        $sqlData = self::$db->query ("SELECT * FROM `content` WHERE `type`='article' AND `slug`='?'", $slug);
        $sqlData->runAfterFetch[] = array ("news", "makeSlug");
        self::$smarty->assign ( "fill", $sqlData->fetchAll() );
    }
}
