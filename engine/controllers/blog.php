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

		if (!empty ($this->args[0]))
		{
			$cacheID = $this->args[0]."|POST";
			$this->smarty->setCacheID ($cacheID);

			if (isset ($_POST["contentID"]))
			{
				blog::addComment (intval($_POST["contentID"]));
				//$this->smarty->clearCache ("post.tpl");
			}

			if (!$this->smarty->isCached ("post.tpl", $cacheID))
			{
				$sqlData = blog::getOnePost ($this->args[0])->fetchAll();
											
				if ($sqlData)
				{
					$sqlData = array_shift ($sqlData);
					
					$comments = comments::get ($sqlData["contentID"]);
					$this->smarty->assign ("comments", $comments);
					
					blog::highlightCode ($sqlData["body"]);		
					$this->smarty->assign ("post", $sqlData);
				}
			}
		} else system::redirect ("/");

		//$this->smarty->clearCache ("post.tpl");
	}
	
	function category()
	{
		if (!isset ($this->args[1]))
			redirect ("/");

		$catSlug = $this->args[1];
		$cacheID = "CATSELECT|$catSlug";

		$this->smarty->setCacheID ($cacheID);

		if (!$this->smarty->isCached ("categoryBlog.tpl", $cacheID))
		{
			$posts = blog::getPostsByCategory ($catSlug)->fetchAll();
			$this->smarty->assign ("posts", $posts);
			$this->smarty->assign ("catName", $posts[0]["catName"]);
		}
		
		system::setParam ("page", "categoryBlog");
	}
	
	function date()
	{
		system::setParam ("page", "blogByDate");
		$cacheID = "DTSELECT";

		if (isset ($this->args[1]))
		{
			$date = preg_replace ("/[^0-9.]/uims", '', $this->args[1]);
			$cacheID = $date."|". $cacheID;
		}

		$this->smarty->setCacheID ($cacheID);

		if (!$this->smarty->isCached ("blogByDate.tpl", $cacheID))
		{
			$posts = blog::getPostsByDate ($date)->fetchAll();
			$this->smarty->assign ("posts", $posts);
			$this->smarty->assign ("date", $date);
		}
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

	function search()
	{
		system::setParam ("page", "search");

		if (!empty ($_GET["text"]))
		{
			$words = $_GET["text"];
			$cacheID = "$words|SEARCH_RES";

			$this->smarty->assign ("searchWord", addslashes($words));

			if (mb_strlen ($words) <= 2)
			{
				$this->smarty->assign ("smallWord", true);
				return;
			}

			$this->smarty->setCacheID ($cacheID);

			if (!$this->smarty->isCached ("search.tpl", $cacheID))
			{
				$res = blog::search ($words);

				if ($res->num_rows > 0)
				{
					$posts = $res->fetchAll();
					$this->smarty->assign ("searchRes", $posts);
				}
			}

			//this->smarty->clearCache ("main.tpl");
			//$this->smarty->clearCache ("search.tpl");

		} else system::redirect ('/');
	}

	function offset()
	{
		if (!isset ($this->args[1]) || !$this->args[1])
			return system::redirect ('/');

		$cacheID = "MAINPAGE|offset_".$this->args[1];
		$this->smarty->setCacheID ($cacheID);

		if (!$this->smarty->isCached ("main.tpl", $cacheID))
		{
			$sqlData = index::mainPage();
			$this->smarty->assign ("posts", $sqlData);
			$this->smarty->assign ("pagination", true);
		}
	}

}
