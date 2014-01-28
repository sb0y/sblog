<?php
/*
 *      user.php
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
class controller_user extends controller_base 
{
	function index()
	{	
		system::redirect ('/');
	}

	function start()
	{

	}
	
	function registration()
	{
		//$this->smarty->clearCache ("userRegistration.tpl");
		system::setParam ("page", "userRegistration");
		
		if (isset ($_POST["email"]))
		{
			system::checkFields (array ("email"=>"e-mail", "nick"=>"Имя", "password1"=>"Пароль", "password2"=>"Проверочный пароль"));
			user::initialVerification();
			
			if (!system::checkErrors())
			{
				$userCrends = user::registerNewUser();
				user::userLogin ($userCrends[0], $userCrends[1]);
			}
		}
	}
	
	function login()
	{
		system::setParam ("page", "loginPage");
		$authRes = false;

		//print_r($this->get);

		if (isset ($this->args[1]) && isset ( $this->args[2] ) )
		{
			$up = user::socialLogin ($this->args[2]);
						
			if ($up)
				$userID = user::socialRegister ($this->args[2], $up);
			
			if (isset ($userID))
				$authRes = user::userLogin ($userID, '-', $this->args[2]);
		}
		
		if (isset ($_POST["email"]))
		{
			system::checkFields (array ("email"=>"E-mail", "password"=>"Пароль"));			

			if (!system::checkErrors())
			{
				$authRes = user::userLogin();
			}
			
			if ( !$authRes )
				return system::redirect ( "/user/passwordRestore" );
		}
		
		if ($authRes)
		{
			if (isset ($_GET["to"]) && $_GET["to"])
				system::redirect (urldecode ($_GET["to"]), 5, "Вы вошли как {$_SESSION["user"]["nick"]}");		
			
			$this->smarty->assign ("authRes", $authRes);
		}
	}
	
	function logout()
	{
		user::logout();
		system::redirect ('/');
	}

	function profile()
	{
		if ( !isset ( $this->get["profile"] ) || !$this->get["profile"] )
			return system::redirect ( '/' );

		$userID = intval ( $this->get["profile"] );
		$systemUserID = 0;

		system::setParam ( "page", "userProfileOpen" );
		$cacheID = "USERPROFILE|USERPANEL|user_" . $userID;

		if ( !empty ( $_SESSION["user"] ) )
		{
			$systemUserID = intval ( $_SESSION["user"]["userID"] );
			$cacheID .= "|logined";
		} else {
			$cacheID .= "|notlogined";
		}

		$this->smarty->setCacheID ( $cacheID );

		if ( !$this->smarty->isCached() )
		{
			if ( !empty ( $_SESSION["user"] ) )
			{
				$user = $this->db->query ( "SELECT * FROM `users` as u LEFT JOIN `friends` as f ON f.`u2`=u.`userID`" . 
					" WHERE u.`userID`=?", $userID )->fetch();
			} else {
				$user = $this->db->query ( "SELECT *, 0 as friendshipID FROM `users` WHERE `userID`=?", $userID )->fetch();
			}

			$this->smarty->assign ( "user", $user );
		}

		$this->smarty->assign ( "userID", $userID );
	}
	
	function controlpanel()
	{
		if (!isset ($_SESSION["user"]))
			return system::redirect ('/');
		
		system::setParam ( "page", "userProfile" );

		$cacheID = "USERPANEL|user_".$_SESSION["user"]["userID"];
		$this->smarty->setCacheID ( $cacheID );
				
		if ( !empty ( $_POST ) )
		{
			//system::checkFields (array ("login"=>"Логин"));
			
			if (!system::checkErrors())
			{
				$post = array_map ("htmlspecialchars", $_POST);
				$post = array_map ("trim", $post);

				if ( !isset ( $_POST["showEmail"] ) || !$_POST["showEmail"] )
					$post["showEmail"] = "N";
				
				$filtredPost = $post;

				if (!empty ($post["password1"]) && !empty ($post["password2"]) && $_SESSION["user"]["source"]=="direct")
				{
					unset ($filtredPost["password1"]);
					unset ($filtredPost["password2"]);
					
					if ( $post["password2"] == $post["password1"] )
					{
						$filtredPost["password"] = md5(md5($post["password1"]));
					} else {
						system::registerEvent ("error", "password2", "Пароли не совпадают.", "Проверочный пароль");
						system::registerEvent ("error", "password1", "Пароли не совпадают.", "Проверочный пароль");
					}
				}

				if ( isset ( $filtredPost["password1"] ) )
					unset ( $filtredPost["password1"] );
				
				if ( isset ( $filtredPost["password2"] ) )
					unset ( $filtredPost["password2"] );

				if (isset ($_FILES["avatar"]) && $_FILES["avatar"]["error"]===0 && $_SESSION["user"]["source"]=="direct")
				{
					$expectedPics = user::processAvatar ($_SESSION["user"]["userID"]);
					$filtredPost["avatar"] = $expectedPics["avatar"]["big"];
					$filtredPost["avatar_small"] = $expectedPics["avatar"]["small"];
				}

				$_SESSION["user"] = array_merge ($_SESSION["user"], $filtredPost);

				foreach ( $filtredPost as $k => $v )
					if (!$v)
						unset ($filtredPost[$k]);

				$this->db->updateTable ("users", $filtredPost, "userID", $_SESSION["user"]["userID"]);
				$this->smarty->clearCurrentCache();
				$this->smarty->clearCache ( null, "USERPROFILE|USERPANEL|user_" . $_SESSION["user"]["userID"] );
				$this->smarty->clearBrowserCache();
			}
		}

		if (isset($_GET["delUserAvatar"]) && $_GET["delUserAvatar"] == "true")
		{
			$this->smarty->clearCurrentCache();
			$this->smarty->clearBrowserCache();
		}
		
		if ( !$this->smarty->isCached() )
		{
			$user = $this->db->query ("SELECT * FROM `users` WHERE `userID`=?", $_SESSION["user"]["userID"])->fetch();
			$this->smarty->assign ("fill", $user);
		}

		if (isset($_GET["delUserAvatar"]) && $_GET["delUserAvatar"] == "true")
		{
			if ($user["avatar"]!="NULL")
				unlink (CONTENT_PATH."/avatars/".$user["avatar"]);
			if ($user["avatar_small"]!="NULL")
				unlink (CONTENT_PATH."/avatars/".$user["avatar_small"]);

			$this->db->updateTable ("users", array ("avatar"=>"NULL","avatar_small"=>"NULL"), 
				"userID", intval ($_SESSION["user"]["userID"]) );

			unset ( $_SESSION["user"]["avatar"], $_SESSION["user"]["avatar_small"] );

			system::redirect ("/{$this->controllerCall}/controlpanel");
		}
	}
	
	function passwordRestore()
	{
		if (isset ($_SESSION["user"]))
			return system::redirect ('/');
			
		system::setParam ("page", "passwordRestore");

		if (isset ($this->get["code"]))
		{
			$code = preg_replace ("/[^a-z0-9 ]/i", '', $this->get["code"]);
			user::processRequestCode ($code);
		} else {
			user::processPasswordRequest();
		}
	}

	function comments()
	{
		if ( !isset ( $_SESSION["user"] ) || !$_SESSION["user"] )
			return system::redirect ('/');

		$offset = 1;
		$limit = "";

		if ( isset ( $this->get [ "offset" ] ) )
			$offset = intval ( $this->get["offset"] );

		$id = intval ( $_SESSION["user"]["userID"] );
		system::setParam ( "page", "userComments" );
		$this->smarty->setCacheID ("USER|USERCOMMENTS|usercomm_$id|usercommoffset_$offset");

		if ( !$this->smarty->isCached() )
		{
			$count = $this->db->query ( "SELECT COUNT(*) FROM `comments` as c ,`users` as u, `content` as co WHERE ".
				"c.`userID`=u.`userID` AND co.`contentID`=c.`contentID` AND u.`userID`=?", $id )->fetch();
			$count = array_shift ( $count );

			$mysqlLimit = core::pagination ( $count, $offset );

			if ( isset ( $mysqlLimit["start"] ) && isset ( $mysqlLimit["end"] ) )
				$limit = "LIMIT ".$mysqlLimit["start"].','.$mysqlLimit["end"];

			$res = $this->db->query ( "SELECT *, c.`body`, c.`dt`, c.`author`, c.`userID` FROM `comments` as c ,`users` as u, `content` as co WHERE ".
				"c.`userID`=u.`userID` AND co.`contentID`=c.`contentID` AND u.`userID`=? ORDER BY c.`dt` DESC $limit", $id );

			$this->smarty->assign ( "comments", $res->fetchAll() );
		}
	}

	function favorites()
	{
		if ( !isset ( $_SESSION["user"] ) || !$_SESSION["user"] )
			return system::redirect ('/');

		$offset = 1;
		$limit = "";

		if ( isset ( $this->get [ "offset" ] ) )
			$offset = intval ( $this->get["offset"] );

		$id = intval ( $_SESSION["user"]["userID"] );
		system::setParam ( "page", "favoritePosts" );
		$this->smarty->setCacheID ( "USER|USERFAVS|userfav_$id|userfavoffset_$offset" );

		if ( !$this->smarty->isCached() )
		{
			$count = $this->db->query ( "SELECT COUNT(*) FROM `favorites` as f WHERE f.`userID`=?", $id )->fetch();
			$count = array_shift ( $count );

			$mysqlLimit = core::pagination ( $count, $offset );

			if ( isset ( $mysqlLimit["start"] ) && isset ( $mysqlLimit["end"] ) )
				$limit = "LIMIT ".$mysqlLimit["start"].','.$mysqlLimit["end"];

			$res = $this->db->query ( "SELECT *, (SELECT COUNT(*) FROM `favorites` WHERE `contentID`=c.`contentID`) as rating ".
			"FROM `favorites` as f, `content` as c WHERE c.`contentID`=f.`contentID` AND f.`userID`=? ORDER BY f.`addDate` DESC $limit", 
			$id );
			$res->runAfterFetchAll[] = array ( "news", "makeSlug" );

			$this->smarty->assign ( "posts", $res->fetchAll() );
		}
	}

	function mail()
	{
		if ( !isset ( $_SESSION["user"] ) && !$_SESSION["user"] )
			return system::redirect ( "/" );

		if ( $this->args[0] != "mail" )
		{
			return system::redirect ( "/" );
		}

		if ( !isset ( $_SESSION["user"]["mail"]["box"] ) )
			$_SESSION["user"]["mail"]["box"] = "inbox";

		$offset = 1;
		$userID = intval ( $_SESSION["user"]["userID"] );

		if ( isset ( $this->get [ "offset" ] ) )
			$offset = intval ( $this->get["offset"] );

		if ( !$this->get )
		{
			system::setParam ( "page", "mail" );
			$this->smarty->setCacheID ( "USER|USERMAIL|usermail_$userID|usermailoffset_$offset|" . 
				$_SESSION["user"]["mail"]["box"] );

			if ( !$this->smarty->isCached() )
				switch ( $_SESSION["user"]["mail"]["box"] )
				{
					case "inbox":
					default:
						$this->smarty->assign ( "emails", user::mailInbox ( $offset )->fetchAll() );
					break;
					case "outbox":
						$this->smarty->assign ( "emails", user::mailOutbox ( $offset )->fetchAll() );
					break;
				}

		} else if ( $this->get["mail"] == "inbox" ) { 

			system::setParam ( "page", "mail" );
			$this->smarty->setCacheID ( "USER|USERMAIL|usermail_$userID|usermailoffset_$offset|mailinbox" );

			if ( !$this->smarty->isCached() )
				$this->smarty->assign ( "emails", user::mailInbox ( $offset )->fetchAll() );

		} else if ( $this->get["mail"] == "outbox" ) { 

			system::setParam ( "page", "mail" );
			$this->smarty->setCacheID ( "USER|USERMAIL|usermail_$userID|usermailoffset_$offset|mailoutbox" );
			
			if ( !$this->smarty->isCached() )
				$this->smarty->assign ( "emails", user::mailOutbox ( $offset )->fetchAll() );

		} else if ( $this->get["mail"] == "write" ) {
			
			system::setParam ( "page", "writeMail" );
			
			if ( isset ( $_POST["sendMail"] ) && $_POST["sendMail"] )
			{
				if ( !system::checkFields ( array ( "receivers"=>"Receivers", "body"=>"Body" ) ) )
					return;

				$subject = "Без темы";
				
				if ( isset ( $_POST["subject"] ) && $_POST["subject"] )
					$subject = $_POST["subject"];

				if ( user::sendUserMail ( $_POST["receivers"], $userID, $subject, $_POST["body"] ) )
				{
					return system::redirect ( "/user/mail" );
				}
			}
		} else if ( $this->get["mail"] == "message" ) {

			system::setParam ( "page", "showMailMessage" );
			$messageID = intval ( $this->get["message"] );
			$this->smarty->setCacheID ( "USER|USERMAIL|usermail_$userID|usermessage_$messageID" );

			//if ( !$this->smarty->isCached() )
			$mail = user::getMailMessage ( $messageID, $userID );

			if ( !$mail )
				return system::redirect ( "/user/mail" );
			
			$this->smarty->assign ( "mail", $mail );
		} else {
			return system::redirect ( "/" );
		}
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}
}

