<?php
/*
 *      index.php
 *
 *      Copyright 2010 Andrei Aleksandovich Bagrintsev <a.bagrintsev@imedia.ru>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
class controller_index extends controller_base 
{
    function start()
    {

    }

	function index()
	{
        if ( isset ( $this->args[0] ) && $this->args[0] != "index" )
        {
            system::setParam ( "page", "layout" );
            $cacheID = $this->args[0] . "|ARTICLE";
            $this->smarty->setCacheID ( $cacheID );

            if ( isset ( $_POST["contentID"] ) && $_POST["contentID"] )
            {
                comments::add ( intval ( $_POST["contentID"] ) );
            }

            $this->smarty->assign ( "isFav", blog::isFavorite ( $this->args[0] ) );

            if ( !$this->smarty->isCached() )
            {
                $sqlData = blog::getOnePost ( $this->args[0], "article" )->fetch();
                                            
                if ( $sqlData )
                {
                    $this->smarty->assign ( "comments", comments::get ( intval ( $sqlData["contentID"] ) ) );
                    $this->smarty->assign ( "post", $sqlData );
                }
            }
        }
        else
        {

            $offset = 1;
            system::setParam ( "page", "list" );

            if ( isset ( $this->get [ "offset" ] ) )
                $offset = intval ( $this->get["offset"] );

            $cacheID = "ARTICLES|artoffset_$offset";
            $this->smarty->setCacheID ( $cacheID );

            if ( !$this->smarty->isCached() )
            {
                $allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` WHERE `type`='article'" )->fetch();
                $this->smarty->assign ( "posts", news::getPosts ( core::pagination ( $allCount["cnt"], $offset ), "article" )->fetchAll() );
            }
        }
	}

	function search()
	{
		system::setParam ( "page", "srch" );

		if ( !empty ( $_GET["text"] ) )
		{
			$words = htmlspecialchars ( addslashes ( $_GET["text"] ) );
			$offset = 1;

			if ( isset ( $this->get [ "offset" ] ) )
				$offset = intval ( $this->get["offset"] );

			$cacheID = "SEARCH_RES|$words|typeArticle|blogsearchoffset_$offset";

			$this->smarty->assign ( "searchWord", $words );

			if ( mb_strlen ( $words ) <= 2 )
			{
				$this->smarty->assign ( "smallWord", true );
				return false;
			}

			$this->smarty->setCacheID ( $cacheID );

			if ( !$this->smarty->isCached() )
			{
				$res = search::searchWithType ( $words, "article" );

				if ( $res->getNumRows() > 0 )
				{
					$posts = $res->fetchAll();
					$this->smarty->assign ( "searchRes", $posts );
				}
			}

		} else system::redirect ('/');
	}

    function rss()
    {
        system::$display = false;
        rss::setHTTPHeaders();

        $this->smarty->setCacheID ("RSS|ARTICLES");

        if ( !$this->smarty->isCached() )
        {
            $sqlData = rss::getLastPostsWithType ( "article" );
            $items = $sqlData->fetchAll();
            $this->smarty->assign ( "items", $items );
        }
        
        echo $this->smarty->fetch ( TPL_PATH . "/rss/rssMain.tpl", "RSS|ARTICLES" );
    }

    function category() 
    {
        if ( !isset ( $this->args[1] ) )
            system::redirect ( "/" );

        system::setParam ("page", "categoryBlog");
        $offset = 1;

        if ( isset ( $this->get [ "offset" ] ) )
            $offset = intval ( $this->get["offset"] );

        $catSlug = $this->args[1];
        $cacheID = "CATSELECT|ARTICLE|$catSlug|catoffset_$offset";

        $this->smarty->setCacheID ( $cacheID );

        if ( !$this->smarty->isCached() )
        {
            $allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` as c, `content_category` as cc, `categories` as cs ".
                "WHERE cc.`contentID`=c.`contentID` AND cs.`categoryID`=cc.`catID` AND c.`type`='article' AND cs.`catSlug`='?'", 
            $this->args[1] )->fetch();
            
            $posts = news::getPostsByCategory ( $catSlug, core::pagination ( $allCount["cnt"], $offset ), "article" )->fetchAll();
            $this->smarty->assign ( "posts", $posts );
            $catName = array_shift ( $posts );
            $catName = array_shift ( $catName["cats"] );
            $this->smarty->assign ( "catName", $catName ["catName"] );
        }
    }

     function date()
    {
        system::setParam ("page", "blogByDate");
        $offset = 1;

        if ( isset ( $this->get [ "offset" ] ) )
            $offset = intval ( $this->get["offset"] );

        $cacheID = "DTSELECT|ARTICLE|dateoffset_$offset";

        if ( isset ( $this->args[1] ) )
        {
            $date = preg_replace ( "/[^0-9.]/uims", '', $this->args[1] );
            $cacheID = $date . "|" . $cacheID;
        }

        $this->smarty->setCacheID ( $cacheID );

        if ( !$this->smarty->isCached() )
        {
            $allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` as c, `content_category` as cc, `categories` as cts WHERE 
            cc.`contentID`=c.`contentID` AND c.`type`='article' AND cts.`categoryID`=cc.`catID` AND c.`showOnSite`='Y' AND c.`dt` >= 
            STR_TO_DATE ('?', '%d.%m.%Y')", $date )->fetch();

            $posts = news::getPostsByDate ( $date, core::pagination ( $allCount["cnt"], $offset ), "article" )->fetchAll();
            $this->smarty->assign ( "posts", $posts );
            $this->smarty->assign ( "date", $date );
        }
    }

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "article", core::model ( "blog" ), core::model ( "comments" ) );
	}

}