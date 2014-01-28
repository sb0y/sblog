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
		$this->posts();
	}
	
	function start()
	{
		
	}

	function writePost()
	{
		system::setParam ("page", "writePost");
		news::getAllCats();
		$fill = array();
		$doRedirect = false;

		$fill = $_POST;

		if (!empty ($_POST["slug"]))
		{
			$fill["slug"] = core::generateSlug ( $_POST["slug"] );

		} else if ( !empty ( $_POST["title"] ) ) {

			$fill["slug"] = core::generateSlug ( $_POST["title"] );
		}

		if ( isset ( $_POST["picRealUpload"] ) )
		{
            $uploadedPics = news::uploadOnePicture ( $fill["slug"] );
		}

		// $fill["poster"] = "";
		if ( isset ( $_FILES["poster"] ) && $_FILES["poster"]["error"] == 0 )
		{
			$uploadedPics = news::uploadOnePicture ( $fill["slug"], "posterImages" );

			if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
				$fill["poster"] = serialize ( $uploadedPics["poster"] );
		}

		if ( isset ( $_POST["savePost"] ) )
		{
			$savedPost = news::writePost ( $fill );

			if ( $savedPost )
			{
				drafts::save ( $savedPost ["contentID"], $_SESSION["user"]["userID"], "news" );
				$doRedirect = true;
			}
		}

		news::showAttachedPics ( $fill );
		$fill['key'] = core::generateKey();
		$this->smarty->assign ( "fill", $fill );
		
		if ( $doRedirect )
			system::redirect ( "/adm/news/posts" );
	}
	
	function addNewCat()
	{
		$res = news::addNewCat();

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
		news::buildList ("content", "news");
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
			$uploadedPics = news::uploadOnePicture ( $fill["slug"] );
		}

		// $fill["poster"] = "";
		if ( isset ( $_FILES["poster"] ) && $_FILES["poster"]["error"] == 0 )
		{
			$uploadedPics = news::uploadOnePicture ( $fill["slug"], "posterImages" );

			if ( isset ( $uploadedPics["poster"] ) && $uploadedPics["poster"] )
				$fill["poster"] = serialize ( $uploadedPics["poster"] );
		}

		if ( isset ($_POST["savePost"]) )
		{
			if ( news::updatePost ( $id, $fill ) )
				$doRedirect = true;
		}

		news::getAllCats ( $id );
		system::setParam ("page", "editPost");
		$sqlData = news::buildForm ("content", array ( "AND `contentID`=$id" ) );
				
		news::showAttachedPics ( $sqlData );

		if ( $doRedirect )
			system::redirect ( "/adm/news/posts" );
	}

	function postsWithComments()
	{
		system::setParam ("page", "posts");
		news::buildList ( "content", "news", array ( "`comments_count`!=0" ) );
	}

	function showPostComments()
	{
		if ( !isset ( $this->get["showPostComments"] ) && !$this->get["showPostComments"] )
			return false;

		$id = intval ( $this->get["showPostComments"] );
		$this->smarty->assign ( "contentID", $id );
		system::setParam ( "page", "commentsList" );

		if ( isset ( $_GET["action"] ) && $_GET["action"] && $_GET["action"] == "delete" )
		{
			$this->db->query ( "UPDATE `content` SET `comments_count`=`comments_count`-1 WHERE `contentID`=?", $id );
		}

		$data = news::buildList ("comments", "news", array ( "`contentID`=$id" ) );
	}

	function resolveUrlById()
	{
		$id = intval ( $this->args[1] );

		if (!$id)
			return false;

		$slug = $this->db->query ( "SELECT `slug` FROM `content` WHERE `contentID`=?", $id )->fetch();
		$slug = $slug["slug"];

		$prefix = '';
		if (isset ($_GET["prefix"]))
			$prefix = urldecode ($_GET["prefix"]);

		if ($slug)
			system::redirect ("/news/$slug$prefix");
		else return false;

		return true;
	}

	function editComment()
	{
		$id = intval ( $_GET["commentID"] );
		system::setParam ( "page", "editComment" );

		if ( isset ( $_POST["body"] ) && $_POST["body"] )
		{
			$contentID = intval ( $_GET["contentID"] );
			$data = array ( "body" => $_POST["body"] );
			news::updateComment ( $id, $data, $contentID );
		}

		news::buildForm ( "comments", "`commentID`=$id" );
	}

	function categories()
	{
		system::setParam ( "page", "categories" );
		news::buildList ( "categories", "", array ( "`catType`='news'" )  );
	}

	function editCategory()
	{
		system::setParam ("page", "editCategory");

		$id = intval ($_GET["categoryID"]);

		if (!empty ($_POST))
		{
			unset ($_POST["savePost"]);
			news::updateCategory ($id, $_POST);
		}

		news::buildForm ("categories", "categoryID`=$id");
	}

	function addCat()
	{
		system::setParam ("page", "addCat");

		if (!empty ($_POST))
		{
			if ( news::addCat ($_POST) )
				system::redirect ( "/adm/news/categories" );
		}
	}
}
