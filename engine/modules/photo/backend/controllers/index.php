<?php
class controller_index extends controller_base 
{
	function index()
	{

	}

	function start()
	{
//		system::setParam ( "page", "layout" );
	}
    function listPage()
    {
        system::setParam ( "page", "listPage" );
        photo::buildList ("photo");
        $id = 0; // @mgenry соблюдай типизацию, мудак блять
        $action = '';
        if(isset($_GET["contentID"]) && isset($_GET["action"]))
        {
            $id = intval ($_GET["contentID"]);
            $action = $_GET["action"];
        }
        if($action == 'delete')
        {
            photo::buildList ("photo");
        }
    }

    function listPageAjax()
    {
        $fill = '';
        $id = '';
        if(isset($_GET["key"]))
        {
            $id = intval ($_GET["key"]);
            $fill = photo::getListByKey($id);
        }
        $this->smarty->setCaching(false);
        system::$display = false;
        $this->smarty->assign("list", $fill);
        $cache_id = 'PHOTO_AJAX_LIST_'.$id;
        $file = MODULES_PATH."/photo/backend/tpl/listAjax.tpl";
        echo $this->smarty->fetch($file, $cache_id);
    }
    
    function deleteAjax()
    {
        system::$display = false;
        $id = '';
        if(isset($_GET["contentID"]))
        {
            $id = intval ($_GET["contentID"]);
            $fill = photo::deleteById($id);
        }
        return false;
    }

	function showPageAjax()
	{
        $fill = '';
        $id = '';
        if(isset($_GET["contentID"]))
        {
            $id = intval ($_GET["contentID"]);
            $fill = photo::getPost($id);
        }
        $this->smarty->setCaching(false);
        system::$display = false;
        $cache_id = 'PHOTO_AJAX_PAGE_'.$id;
        $file = MODULES_PATH."/photo/backend/tpl/pageAjax.tpl";
        $this->smarty->assign ("fill", $fill);
        echo $this->smarty->fetch($file, $cache_id);
    }

    function addPageAjax()
    {
        $fill = array();        
        $slug = "";
        $key = "";
        if(isset($_GET["key"]))
        {
            $key = $_GET["key"];
        }
        if(isset($_POST) && ($_POST))
        {
            $fill = $_POST;
            $contentID = $this->add($fill);
            $this->smarty->clearCache ( null, "PHOTO_AJAX_LIST_".$key );
        }
        $this->smarty->setCaching(false);
        $fill['key'] = $key;
        $this->smarty->assign ("fill", $fill);
        system::$display = false;
        $cache_id = 'PHOTO_AJAX_ADD';        
        $file = MODULES_PATH."/photo/backend/tpl/addAjax.tpl";
        echo $this->smarty->fetch($file, $cache_id);
        
    }

    function editPageAjax()
    {
        $id = intval ($_GET["contentID"]);
        $fill = $_POST;
        if($_POST)
        {
            // echo '<pre>'.print_r($_POST,1).'</pre>';
            if(isset($_POST['x1']) && isset($_POST['y1']) && isset($_POST['w1']) && isset($_POST['h1']) && ($_POST['x1'] != '') && ($_POST['y1'] != '') && ($_POST['w1'] != '') && ($_POST['h1'] != ''))
            {
                $data = array('x1' => $_POST['x1'],'y1' => $_POST['y1'],'w1' => $_POST['w1'],'h1' => $_POST['h1'],'width'=>200,'height'=>200);
                unset($_POST['x1'],$_POST['y1'],$_POST['w1'],$_POST['h1']);
                photo::cropImage($_POST['picture'], $data, '200x200');
                
            }
            if(isset($_POST['x2']) && isset($_POST['y2']) && isset($_POST['w2']) && isset($_POST['h2']) && ($_POST['x2'] != '') && ($_POST['y2'] != '') && ($_POST['w2'] != '') && ($_POST['h2'] != ''))
            {
                $data = array('x1' => $_POST['x2'],'y1' => $_POST['y2'],'w1' => $_POST['w2'],'h1' => $_POST['h2'],'width'=>200,'height'=>140);
                unset($_POST['x2'],$_POST['y2'],$_POST['w2'],$_POST['h2']);
                photo::cropImage($_POST['picture'], $data, '200x140');
                
            }
            $_POST['type'] = 'news';
            photo::updatePost($_POST,$id);
            system::$display = false;
            return true;
        }
        $fill = photo::getPost ( $id );
        $this->smarty->setCaching(false);
        $this->smarty->assign ("fill", $fill);
        system::$display = false;
        $cache_id = 'PHOTO_AJAX_ADD';        
        $file = MODULES_PATH."/photo/backend/tpl/editAjax.tpl";
        echo $this->smarty->fetch($file, $cache_id);
    }


    function editPost()
    {
        system::setParam ( "page", "addPage" );
        $id = intval ($_GET["contentID"]);
        $fill = $_POST;
        if($_POST)
        {
            // echo '<pre>'.print_r($_POST,1).'</pre>';
            if(isset($_POST['x1']) && isset($_POST['y1']) && isset($_POST['w1']) && isset($_POST['h1']) && ($_POST['x1'] != '') && ($_POST['y1'] != '') && ($_POST['w1'] != '') && ($_POST['h1'] != ''))
            {
                $data = array('x1' => $_POST['x1'],'y1' => $_POST['y1'],'w1' => $_POST['w1'],'h1' => $_POST['h1'],'width'=>200,'height'=>200);
                unset($_POST['x1'],$_POST['y1'],$_POST['w1'],$_POST['h1']);
                photo::cropImage($_POST['picture'], $data, '200x200');
                
            }
            if(isset($_POST['x2']) && isset($_POST['y2']) && isset($_POST['w2']) && isset($_POST['h2']) && ($_POST['x2'] != '') && ($_POST['y2'] != '') && ($_POST['w2'] != '') && ($_POST['h2'] != ''))
            {
                $data = array('x1' => $_POST['x2'],'y1' => $_POST['y2'],'w1' => $_POST['w2'],'h1' => $_POST['h2'],'width'=>200,'height'=>140);
                unset($_POST['x2'],$_POST['y2'],$_POST['w2'],$_POST['h2']);
                photo::cropImage($_POST['picture'], $data, '200x140');
                
            }
            $_POST['type'] = 'news';
            photo::updatePost($_POST,$id);
        }
        $fill = photo::getPost ( $id );
        $this->smarty->assign ("fill", $fill);
    }





	function addPage()
	{
        system::setParam ( "page", "addPage" );
        $fill = array();
        $doRedirect = false;
        $slug = "";
        if(isset($_POST) && ($_POST))
        {
            
            $fill = $_POST;
            $this->add($fill);
            if ($savedPost)
            {
                $doRedirect = true;
            }
            if ($doRedirect)
            {
                system::redirect (system::param ("urlBase")."listPage");
            }

        }
        $this->smarty->assign ("fill", $fill);
        
    }
	
    private function add($fill, $doRedirect = false)
    {
        $fill['picture'] = photo::UploadImage($_FILES);
            if(isset($_POST['x1']) && isset($_POST['y1']) && isset($_POST['w1']) && isset($_POST['h1']))
            {
                $data = array('x1' => $_POST['x1'],'y1' => $_POST['y1'],'w1' => $_POST['w1'],'h1' => $_POST['h1']);
                unset($_POST['x1'],$_POST['y1'],$_POST['w1'],$_POST['h1']);
                photo::imageCrop($_POST['picture'], $data, '200x200');
            }
            if(isset($_POST['x2']) && isset($_POST['y2']) && isset($_POST['w2']) && isset($_POST['h2']))
            {
                $data = array('x1' => $_POST['x2'],'y1' => $_POST['y2'],'w1' => $_POST['w2'],'h1' => $_POST['h2']);
                unset($_POST['x2'],$_POST['y2'],$_POST['w2'],$_POST['h2']);
                photo::imageCrop($_POST['picture'], $data, '200x140');
            }
            if(isset($_POST['width']))
            {
                unset($_POST['width']);
            }
            if(isset($_POST['height']))
            {
                unset($_POST['height']);
            }
            if(empty($fill["title"]))
            {
                $fill["title"] = time();
            }
            $fill['type'] = 'news';
            // echo '<pre>'.print_r($fill,1).'</pre>'; exit;
            if (!empty ($fill["slug"]))
            {
                $slug = core::generateSlug ($fill["slug"]);

            } else if (!empty ($fill["title"])) {

                $slug = core::generateSlug ($fill["title"]);
            }
            $fill["slug"] = $slug;
            $savedPost = photo::writePost ( $fill );
            $size = getimagesize(CONTENT_PATH.'/photo/resized/'.$fill['picture']);
            $fill['width'] = $size[0];
            $fill['height'] = $size[1];
            return $savedPost;
    }

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "photo" );
	}

}