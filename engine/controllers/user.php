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
		system::setParam ( "page", "userRegistration" );
		$this->smarty->assign ( "fill", array() );
		
		if ( isset ( $_POST [ "email" ] ) )
		{
			system::checkFields ( array ( "email"=>"e-mail", "nick"=>"Имя", "password1"=>"Пароль", "password2"=>"Проверочный пароль" ) );
			user::initialVerification();
			
			if ( !system::checkErrors() )
			{
				$userCrends = user::registerNewUser();
				user::userLogin ( $userCrends[0], $userCrends[1] );
			}
		}
	}
	
	function login()
	{
		system::setParam ( "page", "auth" );
		$authRes = false;

		//print_r($this->get);
		$through = system::HTTPGet ( "through" );

		if ( $through )
		{
			$up = user::socialLogin ( $through );
						
			if ( $up )
				$userID = user::socialRegister ( $through, $up );
			
			if ( isset ( $userID ) )
				$authRes = user::userLogin ( $userID, '-', $through );
		}
		
		if (isset ($_POST["email"]))
		{
			system::checkFields (array ("email"=>"E-mail", "password"=>"Пароль"));			

			if (!system::checkErrors())
			{
				$authRes = user::userLogin();
			}
			
			if (!$authRes)
				return system::redirect ("/user/passwordRestore");
		}
		
		if ( $authRes )
		{
			if ( isset ( $_GET [ "to" ] ) && $_GET [ "to" ] )
			{
				system::redirect ( urldecode ( $_GET [ "to" ] ), 5, "Вы вошли как <strong>{$_SESSION["user"]["nick"]}</strong>." );
			} else {

				system::redirect ( "/", 5, "Вы вошли как <strong>{$_SESSION["user"]["nick"]}</strong>." );
			}
			
		}
	}
	
	function logout()
	{
		user::logout();
		system::redirect ('/');
	}
	
	function controlpanel()
	{
		if ( !isset  ( $_SESSION [ "user" ] ) )
			return system::redirect ( '/' );

		system::setParam ( "page", "userProfile" );

		$cacheID = "USERPANEL|user_" . $_SESSION["user"]["userID"];
		$this->smarty->setCacheID ( $cacheID );

		if ( $_POST )
		{
			system::checkFields ( array ( "email"=>"E-mail", "nick"=>"Имя" ) );

            if ( !system::checkErrors() )
            {
				$post = array_map ( "htmlspecialchars", $_POST );
				$post = array_map ( "trim", $post );

				if ( !isset ( $_POST [ "showEmail" ] ) || !$_POST [ "showEmail" ] )
					$post [ "showEmail" ] = "N";

				$filtredPost = $post;

				if ( !empty ( $post [ "password1" ] ) && empty ( $post [ "password2" ] ) )
				{
					system::registerEvent ( "error", "password1", "Введите проверочный пароль", "Проверочный пароль");

				} else if ( !empty ( $post [ "password2" ] ) && empty ( $post [ "password1" ] ) ) {

					system::registerEvent ( "error", "password2", "Введите пароль", "Пароль");
				}

				if ( !empty ( $post [ "password1" ] ) && !empty ( $post [ "password2" ] ) 
						&& $_SESSION [ "user" ][ "source" ] == "direct" )
 				{
						unset ( $filtredPost["password1"], $filtredPost["password2"] );

						if ( $post["password2"] == $post["password1"] )
						{
							$filtredPost["password"] = md5(md5($post["password1"]));
						} else {
							system::registerEvent ("error", "password2", "Пароли не совпадают", "Проверочный пароль");
							system::registerEvent ("error", "password1", "Пароли не совпадают", "Проверочный пароль");
						}
				}

				if ( isset ( $filtredPost["password1"] ) )
					unset ( $filtredPost["password1"] );

				if ( isset ( $filtredPost["password2"] ) )
					unset ( $filtredPost["password2"] );

				if ( isset ($_FILES["avatar"]) && $_FILES["avatar"]["error"]===0 && $_SESSION["user"]["source"]=="direct" )
				{
					$expectedPics = user::processAvatar ($_SESSION["user"]["userID"]);
					$filtredPost["avatar"] = $expectedPics["avatar"]["big"];
					$filtredPost["avatar_small"] = $expectedPics["avatar"]["small"];
				}

				$_SESSION["user"] = array_merge ($_SESSION["user"], $filtredPost);

				foreach ( $filtredPost as $k => $v )
					if ( !$v )
						unset ( $filtredPost [ $k ] );

				$this->db->updateTable ("users", $filtredPost, "userID", $_SESSION["user"]["userID"]);
				$this->smarty->clearCache ( null, "USERPROFILE|USERPANEL|user_" . $_SESSION["user"]["userID"] );
			}

            $this->smarty->clearCurrentCache();
            $this->smarty->clearBrowserCache();
 		}

		if ( isset ( $_GET [ "delUserAvatar" ] ) && $_GET [ "delUserAvatar" ] == "true" )
		{
			$this->smarty->clearCurrentCache();
			$this->smarty->clearBrowserCache();
		}

        if ( isset ( $_GET [ "delUserAvatar" ] ) && $_GET [ "delUserAvatar" ] == "true" )
		{
			if ( !$user [ "avatar" ] )
				unlink ( CONTENT_PATH . "/avatars/" . $user["avatar"] );

			if ( !$user [ "avatar_small" ] )
				unlink ( CONTENT_PATH . "/avatars/" . $user["avatar_small"] );

			$this->db->updateTable ("users", array ("avatar"=>"","avatar_small"=>""), 
                                "userID", intval ($_SESSION["user"]["userID"]) );

			$_SESSION["user"]["avatar"] = $_SESSION["user"]["avatar_small"] = "";

			system::redirect ("/{$this->controllerCall}/controlpanel");
		}

		if ( !$this->smarty->isCached() )
		{
			$user = $this->db->query ("SELECT * FROM `users` WHERE `userID`=?", $_SESSION["user"]["userID"])->fetch();
			$this->smarty->assign ("fill", $user);
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
			$offset = intval ( $this->get [ "offset" ] );

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
	
	function passwordRestore()
	{
        if ( isset ( $_SESSION [ "user" ] ) )
            return system::redirect ( '/' );

        system::setParam ( "page", "passwordRestore" );
        $this->smarty->assign ( "fill", array() );

        if ( isset ( $this->get [ "code" ] ) )
        {
            $code = preg_replace ( "/[^a-z0-9 ]/i", '', $this->get [ "code" ] );

            if ( $code )
        	    user::processRequestCode ( $code );
        
        } else if ( isset ( $_GET [ "email" ] ) ) {
            user::processPasswordRequest();
        }
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


	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

}

