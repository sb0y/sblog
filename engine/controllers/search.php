<?php
class controller_search extends controller_base 
{
	function index()
	{
		system::setParam ( "page", "globalSearch" );

		if ( !empty ( $_GET["text"] ) )
		{
			$words = htmlspecialchars ( addslashes ( $_GET["text"] ) );
			$offset = 0;

			if ( isset ( $this->get [ "offset" ] ) )
				$offset = intval ( $this->get["offset"] );

			$cacheID = "SEARCH_RES|$words|blogsearchoffset_$offset";

			$this->smarty->assign ( "searchWord", $words );

			if ( mb_strlen ( $words ) <= 2 )
			{
				$this->smarty->assign ( "smallWord", true );
				return false;
			}

			$this->smarty->setCacheID ( $cacheID );

			if ( !$this->smarty->isCached() )
			{
				$res = search::searchWithType ( $words, "blog" );

				if ( $res->getNumRows() > 0 )
				{
					$posts = $res->fetchAll();
					$this->smarty->assign ( "searchRes", $posts );
				}
			}

		} else system::redirect ('/');
	}

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "search" );
	}

	function start()
	{

	}
}