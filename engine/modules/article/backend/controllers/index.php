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
        blog::buildList ("content", "article");
        $id = 0;
        $action = '';
        if(isset($_GET["contentID"]) && isset($_GET["action"]))
        {
            $id = intval ($_GET["contentID"]);
            $action = $_GET["action"];
        }
        if ( $action == "delete" )
        {
            blog::buildList ("content", "article");
        }
    }

    function editPost()
    {
        $id = intval ($_GET["contentID"]);

        if ( !$id )
            return false;

        $doRedirect = false;

        $fill = $_POST;

        if ( isset ( $_POST["slug"] ) && $_POST["slug"] )
            $fill["slug"] = core::generateSlug ( $_POST["slug"] );

        if ( isset ( $_POST["uploadPicture"] ) )
        {
            $uploadedPics = blog::uploadOnePicture ( $fill["slug"] );
        }

        $fill["poster"] = "";
        if ( isset ( $_FILES["poster"] ) && $_FILES["poster"]["error"] == 0 )
        {
            $uploadedPics = blog::uploadOnePicture ( $fill["slug"], "articleImages" );

            if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
                $fill["poster"] = serialize ( $uploadedPics["poster"] );
        }

        if ( isset ($_POST["savePost"]) )
        {
            if ( blog::updatePost ( $id, $fill ) )
                $doRedirect = true;
        }

        article::getAllCats ( $id );
        system::setParam ("page", "editPost");
        $sqlData = blog::buildForm ("content", array ( "AND `contentID`=$id" ) );
                
        blog::showAttachedPics ( $sqlData );

        if ($doRedirect)
            system::redirect ( system::param ("urlBase") . "listPage" );
    }

	function addPage()
	{
        system::setParam ("page", "addPage");
        article::getAllCats();
        $fill = array();
        $doRedirect = false;

        if (!empty ($_POST["slug"]))
        {
            $fill["slug"] = core::generateSlug ( $_POST["slug"] );

        } else if ( !empty ( $_POST["title"] ) ) {

            $fill["slug"] = core::generateSlug ( $_POST["title"] );
        }

        $fill += $_POST;

        if ( isset ( $_POST["picRealUpload"] ) )
        {
            $uploadedPics = blog::uploadOnePicture ( $fill["slug"] );
        }

        $fill["poster"] = "";
        if ( isset ( $_FILES["poster"] ) && $_FILES["poster"]["error"] == 0 )
        {
            $uploadedPics = blog::uploadOnePicture ( $fill["slug"], "articleImages" );

            if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
                $fill["poster"] = serialize ( $uploadedPics["poster"] );
        }

        if ( isset ( $_POST["savePost"] ) )
        {
            $savedPost = blog::writePost ( $fill, "article" );
            if ($savedPost)
                $doRedirect = true;
        }

        blog::showAttachedPics ( $fill );
        
        $this->smarty->assign ( "fill", $fill );
        
        if ($doRedirect)
            system::redirect ( system::param ("urlBase") . "listPage" );
    }

    function addNewCat()
    {
        $res = blog::addNewCat ( "article" );

        if ( $res !== false )
            echo "Ok|" . $res;
    }

    function categories()
    {
        system::setParam ( "page", "categories" );
        blog::buildList ( "categories", "article" );
    }

    function addCat()
    {
        system::setParam ( "page", "addCat" );

        if ( !empty ( $_POST ) )
        {
            if ( blog::addCat ( $_POST, "article" ) )
                system::redirect ( "/adm/article/categories" );
        }
    }
	
	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "article", core::model ( "blog" ) );
	}

}