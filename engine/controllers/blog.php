<?php
/*
 *      blog.php
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
class controller_blog extends controller_base 
{
	function index()
	{
		system::setParam ("page", "post");

		if ($this->args[0] != "blog")
		{
			$cacheID = $this->args[0]."|POST";
			$this->smarty->setCacheID ( $cacheID );

			if ( isset ( $_POST [ "SLUG" ] ) && isset ( $_POST [ "comment" ] ) )
			{
				blog::addCommentBySLUG ( $_POST [ "SLUG" ] );
			}

			if ( !$this->smarty->isCached() )
			{
				$sqlData = blog::getOnePost ( $this->args[0] )->fetchAll();
											
				if ( $sqlData )
				{
					$sqlData = array_shift ( $sqlData );
					
					$comments = comments::get ( $sqlData [ "contentID" ] );
					$this->smarty->assign ( "comments", $comments );

					blog::highlightCode ( $sqlData [ "body" ] );
					
					$this->smarty->assign ( "item", $sqlData );
				}
			}
		} else system::redirect ("/");
	}

	function start()
	{
	}
	
	function category()
	{
		if ( !isset ( $this->args[1] ) )
			system::redirect ( "/" );

		system::setParam ("page", "categoryBlog");
		$offset = 0;

		if ( isset ( $this->get [ "offset" ] ) )
			$offset = intval ( $this->get["offset"] );

		$catSlug = $this->args[1];
		$cacheID = "CATSELECT|BLOG|$catSlug|catoffset_$offset";

		$this->smarty->setCacheID ( $cacheID );

		if ( !$this->smarty->isCached() )
		{
			$allCount = $this->db->query ( "SELECT COUNT(*) as cnt FROM `content` as c, 
				`content_category` as cc WHERE cc.`contentID`=c.`contentID` AND c.`type`='news' AND c.`slug`='?'", $this->args[1] )->fetch();
			
			$posts = blog::getPostsByCategory ( $catSlug, core::pagination ( $allCount["cnt"], $offset ) )->fetchAll();
			$this->smarty->assign ( "posts", $posts );
			$catName = array_shift ( $posts );
			$catName = array_shift ( $catName["cats"] );
			$this->smarty->assign ( "catName", $catName ["catName"] );
		}
	}
	
	function date()
	{
		system::setParam ("page", "blogByDate");
		$offset = 0;

		if ( isset ( $this->get [ "offset" ] ) )
			$offset = intval ( $this->get["offset"] );

		$cacheID = "DTSELECT|dateoffset_$offset";

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

			$posts = blog::getPostsByDate ( $date, core::pagination ( $allCount["cnt"], $offset ) )->fetchAll();
			$this->smarty->assign ( "posts", $posts );
			$this->smarty->assign ( "date", $date );
		}
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

	function search()
	{
		system::setParam ( "page", "search" );

		if ( !empty ( $_GET [ "text" ] ) )
		{
			$words = $_GET [ "text" ];
			$cacheID = "SEARCH_RES|$words";

			$this->smarty->assign ( "searchWord", addslashes ( $words ) );

			if ( mb_strlen ( $words ) <= 2 )
			{
				$this->smarty->assign ( "smallWord", true );
				return false;
			}

			$this->smarty->setCacheID ( $cacheID );

			if ( !$this->smarty->isCached() )
			{
				$res = blog::search ( $words );

				if ( $res->num_rows > 0 )
				{
					$posts = $res->fetchAll();
					$this->smarty->assign ( "searchRes", $posts );
				}
			}

			//this->smarty->clearCache ("main.tpl");
			//$this->smarty->clearCache ("search.tpl");

		} else system::redirect ( '/' );
	}

	function offset()
	{
		$offset = system::HTTPGet ( "offset" );

		if ( !$offset )
			return system::redirect ( '/' );

		$cacheID = "MAINPAGE|offset_$offset";
		$this->smarty->setCacheID ( $cacheID );

		if (!$this->smarty->isCached ( $cacheID ) )
		{
			$sqlData = index::mainPage();
			$this->smarty->assign ( "posts", $sqlData );
		}
	}

}
