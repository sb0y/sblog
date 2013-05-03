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
		$this->posts();
	}

	function writePost()
	{
		system::setParam ("page", "writePost");
		blog::getAllCats();
		$fill = array();
		$doRedirect = false;

		if (!empty ($_POST["slug"]))
		{
			$_POST["slug"] = blog::handlePostName ($_POST["slug"]);

		} else if (!empty ($_POST["title"])) {

			$_POST["slug"] = blog::handlePostName ($_POST["title"]);
		}

		if (isset ($_GET["draftID"]) || isset ($_GET["draftName"]))
		{
			$draftCall = isset ($_GET["draftID"]) ? intval ($_GET["draftID"]) : $_GET["draftName"];
			$fill = blog::loadDraft ($draftCall);
		} else {

			$fill = $_POST;
		}

		if (isset ($_POST["picRealUpload"]))
		{
			$uploadedPics = blog::uploadOnePicture ($_POST["slug"]);
		}

		if (isset ($_POST["savePost"]))
		{
			$savedPost = blog::writePost ($_POST);
			if ($savedPost)
				$doRedirect = true;
		}

		blog::showAttachedPics ($fill);
		
		$this->smarty->assign ("fill", $fill);
		
		if ($doRedirect)
			system::redirect (system::param ("urlBase")."posts");
	}
	
	function addNewCat()
	{
		echo blog::addNewCat();
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

	function posts()
	{
		system::setParam ("page", "posts");
		//$this->smarty->setCacheID ("MAINPAGE");
		blog::buildList ("content");
    }

	function editPost()
	{
		$id = intval ($_GET["contentID"]);
		$doRedirect = false;

		if (isset ($_POST["savePost"]))
		{
			blog::updatePost ($id, $_POST);
			$doRedirect = true;
		}

		if (isset ($_POST["uploadPicture"]))
		{
			$uploadedPics = blog::uploadOnePicture ($_POST["slug"]);
		}

		blog::getAllCats ($id);
		system::setParam ("page", "editPost");
		$sqlData = blog::buildForm ("content", "AND `contentID`=$id");
				
		blog::showAttachedPics ($sqlData);
	
		$this->smarty->clearCache (null, "MAINPAGE");
        $this->smarty->clearCache (null, "SEARCH_RES");
        $this->smarty->clearCache (null, "RSS");
        $this->smarty->clearCache (null, $sqlData["slug"]);
    
        if ($doRedirect)
			system::redirect (system::param ("urlBase")."posts");
	}

	function postsWithComments()
	{
		system::setParam ("page", "posts");
		blog::buildList ("content", "AND `comments_count`!=0");
	}

	function showPostComments()
	{
		$id = intval ($this->args[1]);
		$this->smarty->assign ("contentID", $id);
		system::setParam ("page", "commentsList");
		blog::buildList ("comments", "AND `contentID`=$id");
	}

	function resolveUrlById()
	{
		$id = intval ($this->args[1]);

		if (!$id)
			return false;

		$slug = $this->db->query ("SELECT `slug` FROM `content` WHERE `contentID`=?", $id)->fetch();
		$slug = $slug["slug"];

		$prefix = '';
		if (isset ($_GET["prefix"]))
			$prefix = urldecode ($_GET["prefix"]);

		if ($slug)
			system::redirect ("/blog/$slug$prefix");
		else return false;

		return true;
	}

	function editComment()
	{
		$id = intval ($_GET["commentID"]);

		if (!empty ($_POST))
		{
			blog::updateComment ($id);
		}

		blog::buildForm ("comments", "AND `commentID`=$id");
		system::setParam ("page", "editComment");
	}

	function categories()
	{
		system::setParam ("page", "categories");
		blog::buildList ("categories");
	}

	function editCategory()
	{
		system::setParam ("page", "editCategory");

		$id = intval ($_GET["categoryID"]);

		if (!empty ($_POST))
		{
			unset ($_POST["savePost"]);
			blog::updateCategory ($id, $_POST);
		}

		blog::buildForm ("categories", "AND `categoryID`=$id");
	}

	function addCat()
	{
		system::setParam ("page", "addCat");

		if (!empty ($_POST))
			blog::addCat ($_POST);
	}
}
