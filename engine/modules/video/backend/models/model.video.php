<?php
class video extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

    function requestModels (&$modelsNeeded)
    {
        $modelsNeeded = array("video");
    }

    public static function checkPostErrors ( $post )
    {
        if (empty ($post["title"]))
        {
            system::registerEvent ("error", "title", "Заголовок не может быть пустым", "Заголовок поста");
        }

        if (system::checkErrors())
        {
            return false;
        }

        return true;
    }

    public static function postExist ($key, $value)
    {
        $sql = self::$db->query ("SELECT `$key` FROM `video` WHERE `$key`='$value'");

        if ($sql->num_rows > 0)
        {
            return true;
        }

        return false;
    }

    public static function writePost ($post)
    {
        if (self::postExist ("slug", $post["slug"]))
        {
            system::registerEvent ("error", "slug", "Такой адрес поста уже занят", "URL");
        }

        if ( !self::checkPostErrors ( $post ) )
        {
            return false;
        }

        $post["author"] = $_SESSION["user"]["nick"];
        $post["userID"] = $_SESSION["user"]["userID"];
        $content = array();
        foreach ($post as $k=>$v)
        {
            $v = self::$db->escapeString ($v);
            $content[$k] = "`$k`='$v'";
        }
        $content['dt'] = "`dt`='".date("Y-m-d")."'";
        $new_picture_name = time();
        file_put_contents(ROOT_PATH."/content/videoPreview/".$new_picture_name.'.jpg', file_get_contents($post['pictures']));
        $content['pictures'] = "`pictures`='".$new_picture_name.".jpg"."'";;
        unset($content['savePost'],$content['url']);
//        echo "INSERT INTO `content` SET ".implode (", ", $content);
//        echo '<pre>'.print_r($post,1).'</pre>';
//        exit;
        self::$db->query ( "INSERT INTO `video` SET " . implode (", ", $content) );
        $id = self::$db->insert_id();
        
        self::$smarty->clearCache (null, "MAINPAGE|offset_0");
        self::$smarty->clearCache (null, "mainpage|offset_0");
        self::$smarty->clearCache (null, "MODULE_VIDEO");
        self::$smarty->clearCache (null, "SEARCH_RES");
        self::$smarty->clearCache (null, "RSS");

        return 'test';
    }

    public static function updatePost($data, $id)
    {
        if(!isset($data['showOnSite']))
        {
            $data['showOnSite'] = 'N';
        }
        $q = self::$db->query ( "UPDATE `video` SET  `title`='?', `slug`='?', `showOnSite`='?',`description`='?', `comments_count`='?', `views_count`='?', `video`='?', `pictures`='?', `editedByID`=?, `editedByNick`='?', `editedOn`=NOW() WHERE `contentID`=?", $data["title"], $data["slug"], $data["showOnSite"], $data["description"], $data["comments_count"], $data["views_count"], $data["video"], $data["pictures"], $_SESSION["user"]["userID"], $_SESSION["user"]["nick"], $id );
        self::$smarty->clearCache ("main.tpl", "MAINPAGE|offset_0");
        self::$smarty->clearCache (null, "mainpage|offset_0");
        self::$smarty->clearCache (null, "MODULE_VIDEO");
        self::$smarty->clearCache (null, "SEARCH_RES");
        self::$smarty->clearCache (null, "RSS");

        return $q;

    }




    public static function getPost($contentID)
    {
        $sqlData = self::$db->query ("SELECT * FROM `video` WHERE `contentID` = ".$contentID)->fetch();
        self::$smarty->assign ("fill", $sqlData);
    }

    public static function buildList ($target, $clause='')
    {
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

                    if ($target=="content")
                    {
                        self::$db->query ("DELETE FROM `video` WHERE `contentID`=?", $id);
                        self::$smarty->clearCache (null, "MAINPAGE");
                        self::$smarty->clearCache (null, "RSS");
                        self::$smarty->clearCache (null, "SEARCH_RES");
                    }
                    break;
            }
        }

        if (isset ($_POST["groupDelete"]) && !empty ($_POST["rows"]))
            self::$db->query ("DELETE FROM `$target` WHERE `".$columns[0]["Field"]."` IN (".
            implode (",",$_POST["rows"]).")");

        $mysqlLimits = array();
        $offset = 0;
        $allCount = self::$db->query ("SELECT COUNT(*) as cnt FROM `$target` WHERE 1 ".$clause)->fetch();
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
            else if (($_GET["direction"] == "ASC"))
                $direction = "DESC";
        }

        if (isset ($_GET["sort"]) && $_GET["sort"])
        {
            $sort = $_GET["sort"];
        }

        $pageCompose->readInputData ($offset, 20);
        $mysqlLimits = $pageCompose->calculateOffset();
        $pages = $pageCompose->genPages();
        self::$smarty->assign ("pages", $pages);
        self::$smarty->assign ("direction", $direction);
        self::$smarty->assign ("sort", $sort);
        self::$smarty->assign ("allCount", $allCount["cnt"]);

        $sqlData = self::$db->query ("SELECT * FROM `$target` WHERE 1 $clause ORDER BY `$sort` $direction LIMIT {$mysqlLimits["start"]},{$mysqlLimits["end"]}")->fetchAll();

        self::$smarty->assign ("list", $sqlData);

        return $sqlData;
    }

}