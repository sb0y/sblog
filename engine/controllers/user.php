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
		
	}
	
	function registration()
	{
		$this->smarty->clearCache ("userRegistration.tpl");
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

		if (isset ($this->args[1]) && $this->args[2])
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
				
				if (!$authRes)
					return system::redirect ("/user/passwordRestore");
			}
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
	
	function controlpanel()
	{
		if (!isset ($_SESSION["user"]))
			system::redirect ('/');
		
		system::setParam ("page", "userProfile");
		
		if (!empty ($_POST))
		{
			//system::checkFields (array ("login"=>"Логин"));
			
			if (!system::checkErrors())
			{
				$_POST = array_map ("trim", $_POST);
				
				$filtredPost["nick"] = htmlspecialchars ($_POST["nick"]);
				$filtredPost["email"] = htmlspecialchars ($_POST["email"]);
				
				if (!empty ($_POST["password1"]) && !empty ($_POST["password2"]) && $_SESSION["user"]["source"]=="direct")
				{
					unset ($filtredPost["password1"]);
					unset ($filtredPost["password2"]);
					
					if ($_POST["password2"] == $_POST["password1"])
					{
						$filtredPost["password"] = md5(md5($_POST["password1"]));
					} else {
						system::registerEvent ("error", "password2", "Пароли не совпадают.", "Проверочный пароль");
						system::registerEvent ("error", "password1", "Пароли не совпадают.", "Проверочный пароль");
					}
				}
				
				if (isset ($_FILES["avatar"]) && $_FILES["avatar"]["error"]===0 && $_SESSION["user"]["source"]=="direct")
				{
					$expectedPics = user::processAvatar ($_SESSION["user"]["userID"]);
					$filtredPost["avatar"] = $expectedPics["avatar"]["big"];
					$filtredPost["avatar_small"] = $expectedPics["avatar"]["small"];
				}

				$_SESSION["user"] = array_merge ($_SESSION["user"], $filtredPost);

				foreach ($filtredPost as $k=>$v)
					if (!$v)
						unset ($filtredPost[$k]);
						
				$this->db->updateTable ("users", $filtredPost, "userID", $_SESSION["user"]["userID"]);
			}
		}
		
		$user = $this->db->query ("SELECT * FROM `users` WHERE `userID`=?", $_SESSION["user"]["userID"])->fetch();
		$this->smarty->assign ("fill", $user);
	}
	
	function passwordRestore()
	{
		if (isset ($_SESSION["user"]))
			system::redirect ('/');
			
		system::setParam ("page", "passwordRestore");

		if (isset ($this->args[1]) && isset ($this->args[2]))
		{
			$code = preg_replace ("/[^a-z0-9 ]/i", '', $this->args[2]);
			user::processRequestCode ($code);
		} else {
			user::processPasswordRequest();
		}
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

}

