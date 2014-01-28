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
        blog::buildList ("video");
        $id = 0; // @mgenry соблюдай типизацию, мудак блять
        $action = '';
        if(isset($_GET["contentID"]) && isset($_GET["action"]))
        {
            $id = intval ($_GET["contentID"]);
            $action = $_GET["action"];
        }
        if($action == 'delete')
        {
            blog::buildList ("video");
        }
    }

    function editPost()
    {
        system::setParam ( "page", "editPage" );
        $id = intval ($_GET["contentID"]);
        $fill = $_POST;
        if($_POST)
        {
            video::updatePost($_POST,$id);
        }
        video::getPost ( $id );
    }

    function addPage()
    {
        system::setParam ( "page", "addPage" );
        $fill = array();
        $doRedirect = false;
        $slug = "";
        if($_POST)
        {
            $fill = $_POST;
            if (!empty ($_POST["slug"]))
            {
                $slug = core::generateSlug ($_POST["slug"]);

            } else if (!empty ($_POST["title"])) {

                $slug = core::generateSlug ($_POST["title"]);
            }
            $fill["slug"] = $slug;
            $savedPost = video::writePost ( $fill );
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
	function addPageAjax()
	{
        system::setParam ( "page", "ajax" );
        if($_POST)
        {
            
            $fill = array();
            $slug = "";
            $fill = $_POST;
            if (!empty ($_POST["slug"]))
            {
                $slug = core::generateSlug ($_POST["slug"]);

            } else if (!empty ($_POST["title"])) {

                $slug = core::generateSlug ($_POST["title"]);
            }
            $fill["slug"] = $slug;
            $savedPost = video::writePost ( $fill );
            // $this->smarty->assign ("fill", $fill);
            return true;

        }
        
    }
	
	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "video" );
	}

}