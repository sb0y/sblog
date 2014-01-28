<?php
/*
 *      news.php
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
class controller_news extends controller_base 
{
	function index()
	{
		system::setParam ("page", "post");

		if ( $this->args[0] != "index" && ( isset ( $this->args[1] ) && $this->args[1] ) )
		{
			$cacheID = $this->args[0] . "_newsdate|" . $this->args[1] . "|NEWS";
			$this->smarty->setCacheID ( $cacheID );

            if ( isset ( $_POST["contentID"] ) && $_POST["contentID"] )
			{
				comments::add ( intval ( $_POST["contentID"] ) );
			}

			$this->smarty->assign ( "isFav", news::isFavorite ( $this->args[1] ) );

			if ( !$this->smarty->isCached() )
			{
				$sqlData = news::getOnePost ( $this->args[1], $this->args[0] )->fetch();
											
				if ( $sqlData )
				{
					$this->smarty->assign ( "comments", comments::get ( $sqlData["contentID"] ) );
					$this->smarty->assign ( "post", $sqlData );
				}
			}
		} else system::redirect ( "/" );
	}

	function start()
	{
		
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
		$cacheID = "CATSELECT|NEWS|$catSlug|catoffset_$offset";

		$this->smarty->setCacheID ( $cacheID );

		if ( !$this->smarty->isCached() )
		{
			$allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` as c, `content_category` as cc, `categories` as cs ".
				"WHERE cc.`contentID`=c.`contentID` AND cs.`categoryID`=cc.`catID` AND c.`type`='news' AND cs.`catSlug`='?'", 
			$this->args[1] )->fetch();
			
			$posts = news::getPostsByCategory ( $catSlug, core::pagination ( $allCount["cnt"], $offset ) )->fetchAll();
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

		$cacheID = "DTSELECT|NEWS|dateoffset_$offset";

		if ( isset ( $this->args[1] ) )
		{
			$date = preg_replace ( "/[^0-9.]/uims", '', $this->args[1] );
			$cacheID = $date . "|" . $cacheID;
		}

		$this->smarty->setCacheID ( $cacheID );

		if ( !$this->smarty->isCached() )
		{
			$allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` as c, `content_category` as cc, `categories` as cts WHERE 
			cc.`contentID`=c.`contentID` AND c.`type`='news' AND cts.`categoryID`=cc.`catID` AND c.`showOnSite`='Y' AND c.`dt` >= 
			STR_TO_DATE ('?', '%d.%m.%Y')", $date )->fetch();

			$posts = news::getPostsByDate ( $date, core::pagination ( $allCount["cnt"], $offset ) )->fetchAll();
			$this->smarty->assign ( "posts", $posts );
			$this->smarty->assign ( "date", $date );
		}
	}
	
	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ( "search" );
	}

	function search()
	{
		system::setParam ( "page", "search" );

		if ( !empty ( $_GET["text"] ) )
		{
			$words = htmlspecialchars ( addslashes ( $_GET["text"] ) );
			$offset = 1;

			if ( isset ( $this->get [ "offset" ] ) )
				$offset = intval ( $this->get["offset"] );

			$cacheID = "SEARCH_RES|$words|typeNews|newssearchoffset_$offset";

			$this->smarty->assign ( "searchWord", $words );

			if ( mb_strlen ( $words ) <= 2 )
			{
				$this->smarty->assign ( "smallWord", true );
				return false;
			}

			$this->smarty->setCacheID ( $cacheID );

			if ( !$this->smarty->isCached() )
			{
				$res = search::searchWithType ( $words, "news" );

				if ( $res->getNumRows() > 0 )
				{
					$posts = $res->fetchAll();
					$this->smarty->assign ( "searchRes", $posts );
				}
			}

		} else system::redirect ('/');
	}

	function offset()
	{
		if ( !isset ( $this->args[1] ) || !$this->args[1] )
			return system::redirect ( '/' );

		$cacheID = "MAINPAGE|offset_" . $this->args[1];
		$this->smarty->setCacheID ( $cacheID );

		if ( !$this->smarty->isCached() )
		{
			$sqlData = index::mainPage();
			$this->smarty->assign ( "posts", $sqlData );
			$this->smarty->assign ( "pagination", true );
		}
	}

	function rss()
	{
		system::$display = false;
		rss::setHTTPHeaders();

		$this->smarty->setCacheID ( "RSS|NEWS" );

		if ( !$this->smarty->isCached() )
		{
			$sqlData = rss::getLastPostsWithType ( "news" );
			$items = $sqlData->fetchAll();
			$this->smarty->assign ( "items", $items );
		}

		echo $this->smarty->fetch ( TPL_PATH . "/rss/rssMain.tpl", "RSS|NEWS" );
	}

}