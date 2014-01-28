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
	
	function start()
	{
		
	}

	function writePost()
	{
		system::setParam ("page", "writePost");
		blog::getAllCats();
		$fill = array();
		$doRedirect = false;

		if (!empty ($_POST["slug"]))
		{
			$fill["slug"] = core::generateSlug ( $_POST["slug"] );

		} else if ( !empty ( $_POST["title"] ) ) {

			$fill["slug"] = core::generateSlug ( $_POST["title"] );
		}

		if (isset ($_GET["draftID"]) || isset ($_GET["draftName"]))
		{
			$draftCall = isset ($_GET["draftID"]) ? intval ( $_GET["draftID"] ) : $_GET["draftName"];
			$fill = blog::loadDraft ($draftCall);
		} else {

			$fill += $_POST;
		}

		if ( isset ( $_POST["picRealUpload"] ) )
		{
            $uploadedPics = blog::uploadOnePicture ( $fill["slug"] );
		}

		$fill["poster"] = "";
		if ( isset ( $_FILES["poster"] ) && $_FILES["poster"]["error"] == 0 )
		{
			$uploadedPics = blog::uploadOnePicture ( $fill["slug"], "posterImages" );

			if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
				$fill["poster"] = serialize ( $uploadedPics["poster"] );
		}

		if (isset ($_POST["savePost"]))
		{
			$savedPost = blog::writePost ( $fill );
			if ($savedPost)
				$doRedirect = true;
		}

		blog::showAttachedPics ( $fill );
		
		$this->smarty->assign ( "fill", $fill );
		
		if ($doRedirect)
			system::redirect (system::param ("urlBase")."posts");
	}
	
	function addNewCat()
	{
		$res = blog::addNewCat();

		if ( $res !== false )
			echo "Ok|" . $res;
	}

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array();
	}

	function posts()
	{
		system::setParam ("page", "posts");
		//$this->smarty->setCacheID ("MAINPAGE");
		blog::buildList ("content", "news");
    }

	function editPost()
	{
		$id = intval ( $_GET["contentID"] );

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
			$uploadedPics = blog::uploadOnePicture ( $fill["slug"], "posterImages" );

			if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
				$fill["poster"] = serialize ( $uploadedPics["poster"] );
		}

		if ( isset ($_POST["savePost"]) )
		{
			if ( blog::updatePost ( $id, $fill ) )
				$doRedirect = true;
		}

		blog::getAllCats ( $id );
		system::setParam ("page", "editPost");
		$sqlData = blog::buildForm ("content", array ( "AND `contentID`=$id" ) );
				
		blog::showAttachedPics ( $sqlData );

		if ($doRedirect)
			system::redirect (system::param ("urlBase")."posts");
	}

	function postsWithComments()
	{
		system::setParam ("page", "posts");
		blog::buildList ("content", "news", array ( "`comments_count`!=0" ) );
	}

	function showPostComments()
	{
		$id = intval ($this->args[1]);
		$this->smarty->assign ("contentID", $id);
		system::setParam ("page", "commentsList");
		blog::buildList ("comments", "news", array ( "`contentID`=$id" ) );
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
		system::setParam ("page", "editComment");

		if (!empty ($_POST))
		{
			blog::updateComment ($id);
		}

		blog::buildForm ("comments", "AND `commentID`=$id");
	}

	function categories()
	{
		system::setParam ( "page", "categories" );
		blog::buildList ( "categories", "news" );
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

		blog::buildForm ("categories", "categoryID`=$id");
	}

	function addCat()
	{
		system::setParam ("page", "addCat");

		if (!empty ($_POST))
		{
			if ( blog::addCat ($_POST) )
				system::redirect ( "/adm/blog/categories" );
		}
	}
}
