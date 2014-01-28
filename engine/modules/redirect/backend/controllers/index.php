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
        blog::buildList ("redirect");
        $id = 0;
        $action = '';
        if(isset($_GET["contentID"]) && isset($_GET["action"]))
        {
            $id = intval ($_GET["contentID"]);
            $action = $_GET["action"];
        }
        if ( $action == "delete" )
        {
            blog::buildList ("redirect");
        }
    }

    function edit()
    {
        $id = intval ($_GET["entryID"]);
        $doRedirect = false;
        system::setParam ("page", "addPage");

        if ( !$id )
            return system::redirect ( "/adm/listPage" );

        if ( isset ( $_POST ) && $_POST )
        {
            $url = $_POST["URL"];
            if ( $this->db->query ( "UPDATE `redirect` SET `URL`='?' WHERE `entryID`=?", $url, $id ) )
                $doRedirect = true;
        }

        $sqlData = blog::buildForm ("redirect", array ( "AND `entryID`=$id" ) );

        if ($doRedirect)
            system::redirect ( "/adm/redirect/listPage" );
    }

	function add()
	{
        system::setParam ("page", "addPage");
        $fill = array();
        $doRedirect = false;

        if ( isset ( $_POST ) && $_POST )
        {
            $url = $_POST["URL"];
            $code = redirect::generateRandomString ( 10 );
            if ( $this->db->query ( "INSERT INTO `redirect` (`URL`,`code`) VALUES ('?','?')", $url, $code ) )
                $doRedirect = true;
        }
        
        $this->smarty->assign ( "fill", $fill );
        
        if ( $doRedirect )
            system::redirect ( "/adm/redirect/listPage" );
    }

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ("redirect");
	}

}