<?php
class photo extends model_base
{
	public static function start()
	{
		//self::$smarty->runBeforeDisplay[] = array ("index", "::", "loadCatsMenu");
	}

    function requestModels (&$modelsNeeded)
    {
        $modelsNeeded = array("photo");
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
        $sql = self::$db->query ("SELECT `$key` FROM `photo` WHERE `$key`='$value'");

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
        unset($post['savePost']);
        $post["author"] = $_SESSION["user"]["nick"];
        $post["userID"] = $_SESSION["user"]["userID"];
        $content = array();
        foreach ($post as $k=>$v)
        {
            $v = self::$db->escapeString ($v);
            $content[$k] = "`$k`='$v'";
        }
        $content['dt'] = "`dt`='".date("Y-m-d")."'";
        self::$db->query ( "INSERT INTO `photo` SET " . implode (", ", $content) );
        $id = self::$db->insert_id();
        
        self::$smarty->clearCache (null, "MAINPAGE|offset_0");
        self::$smarty->clearCache (null, "mainpage|offset_0");
        self::$smarty->clearCache (null, "MODULE_PHOTO");
        self::$smarty->clearCache (null, "SEARCH_RES");
        self::$smarty->clearCache (null, "RSS");

        return $id;
    }

    public static function updatePost($data, $id)
    {
        if(!isset($data['showOnSite']))
        {
            $data['showOnSite'] = 'N';
        }
        $q = self::$db->query ( "UPDATE `photo` SET  `title`='?',`title`='?', `slug`='?', `showOnSite`='?',`description`='?', `comments_count`='?', `picture`='?', `type`='?', `editedByID`=?, `editedByNick`='?', `editedOn`=NOW() WHERE `contentID`=?", 
                                            $data["title"],$data["key"], $data["slug"], $data["showOnSite"], $data["description"], $data["comments_count"], $data["picture"], $data["type"], $_SESSION["user"]["userID"], $_SESSION["user"]["nick"], $id );
        self::$smarty->clearCache ("main.tpl", "MAINPAGE|offset_0");
        self::$smarty->clearCache (null, "mainpage|offset_0");
        self::$smarty->clearCache (null, "MODULE_PHOTO");
        self::$smarty->clearCache (null, "SEARCH_RES");
        self::$smarty->clearCache (null, "RSS");

        return $q;

    }

    public static function deleteById($contentID)
    {
        $photo = self::$db->query("SELECT * FROM `photo` WHERE `contentID`=?", $contentID)->fetch();
        self::$db->query ("DELETE FROM `photo` WHERE `contentID`=?", $contentID);
        unlink(CONTENT_PATH.'/photo/original/'.$photo['picture']);
        unlink(CONTENT_PATH.'/photo/resized/'.$photo['picture']);
        unlink(CONTENT_PATH.'/photo/200x200/'.$photo['picture']);
        unlink(CONTENT_PATH.'/photo/200x140/'.$photo['picture']);
        return true;
    }


    public static function getPost($contentID)
    {
        $sqlData = self::$db->query ("SELECT * FROM `photo` WHERE `contentID` = ".$contentID)->fetch();
        $size = getimagesize(CONTENT_PATH.'/photo/resized/'.$sqlData['picture']);
        $sqlData['width'] = $size[0];
        $sqlData['height'] = $size[1];
        // echo '<pre>'.print_r($sqlData,1).'</pre>';
        // self::$smarty->assign ("fill", $sqlData);
        return $sqlData;
    }

    public static function getListByKey($key = false)
    {
    
        return self::$db->query ("SELECT * FROM `photo` WHERE `key` = ".$key)->fetchAll();
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
                    $photo = self::$db->query("SELECT * FROM `$target` WHERE `?`=?", $keyName, $id)->fetch();
                    if($photo)
                    {
                        self::$db->query ("DELETE FROM `$target` WHERE `?`=?", $keyName, $id);
                        unlink(CONTENT_PATH.'/photo/original/'.$photo['picture']);
                        unlink(CONTENT_PATH.'/photo/resized/'.$photo['picture']);
                        unlink(CONTENT_PATH.'/photo/200x200/'.$photo['picture']);
                        unlink(CONTENT_PATH.'/photo/200x140/'.$photo['picture']);
                    }
                    if ($target=="content")
                    {
                        self::$db->query ("DELETE FROM `photo` WHERE `contentID`=?", $id);
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

    public static function UploadImage($FILES)
    {
        $uploaddir = CONTENT_PATH.'/photo/';
        // $uploadfile = $uploaddir . basename($FILES['picture']['name']);
        $extensions = array(
            IMAGETYPE_GIF => "gif",
            IMAGETYPE_JPEG => "jpg",
            IMAGETYPE_PNG => "png",
            IMAGETYPE_BMP => "bmp",
        );
        $size = getimagesize($FILES['picture']['tmp_name']);
        // echo "<pre>".print_r($size,1)."</pre>";
        
        $uploadfile = time().image_type_to_extension($size['2']);
        $new_file = $uploaddir.'original/'.$uploadfile;
        move_uploaded_file($FILES['picture']['tmp_name'], $new_file);
        if($size[0] > 900)
        {
            self::resizeImage($uploadfile, 900, false, 'resized');
        }
        else 
        {
            copy($new_file, $uploaddir.'resized/'.$uploadfile);
        }
        self::resizeImage($uploadfile, 200, 200, '200x200');
        self::resizeImage($uploadfile, 200, 140, '200x140');
        return $uploadfile;
    }

    public static function resizeImage($image, $width=false, $height=false, $folder = 'resized')
    {
        $size = getimagesize(CONTENT_PATH.'/photo/original/'.$image);
        $ratio_orig = $size[0]/$size[1];
        if(!$height)
        {
            $height = $width/$ratio_orig;
        }
        if(!$width)
        {
            $width = $height/$ratio_orig;
        }
        $src = imagecreatefromstring(file_get_contents(CONTENT_PATH.'/photo/original/'.$image));
        $dst = imagecreatetruecolor($width,$height);
        imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);
        imagedestroy($src);
        imagepng($dst,CONTENT_PATH.'/photo/'.$folder.'/'.$image); // adjust format as needed
        imagedestroy($dst);
    }

    public static function cropImage($image, $data, $folder)
    {
        $size = getimagesize(CONTENT_PATH.'/photo/resized/'.$image);
        $src = imagecreatefromstring(file_get_contents(CONTENT_PATH.'/photo/resized/'.$image));
        $dst = imagecreatetruecolor((int)$data['w1'],(int)$data['h1']);
        imagecopyresampled($dst,$src,0,0,(int)$data['x1'],(int)$data['y1'],(int)$data['w1'],(int)$data['h1'],(int)$data['w1'],(int)$data['h1']);
        imagedestroy($src);
        imagepng($dst,CONTENT_PATH.'/photo/'.$folder.'/'.$image); // adjust format as needed
        imagedestroy($dst);
    }


}