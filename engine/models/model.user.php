<?php
class user extends model_base
{	
	public static function start ()
	{

	}
	
	public static function initialVerification()
	{			
		if (@$_POST["password1"] != @$_POST["password2"])
		{
			system::registerEvent ("error", "password2", "Пароли не совпадают.", "Проверочный пароль");
			system::registerEvent ("error", "password1", "Пароли не совпадают.", "Проверочный пароль");
		}
			
		$usrChk = self::$db->query ("SELECT `email` FROM `users` WHERE `email`='?' AND `source`='direct'", $_POST["email"]);
			
		if ($usrChk->num_rows > 0)
			system::registerEvent ("error", "email", "Пользователь с таким e-mail уже существует.", "e-mail");
			
		if ($_POST["email"]==="0" || !empty($_POST["email"]))
		{			
			if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
				system::registerEvent ("error", "email", "Адрес электронной почты введён не правильно.", "e-mail");
		}
			
			
		self::updateSmarty();
	}
	
	public static function updateSmarty()
	{
		self::$smarty->assign ("errors", system::getEvents ("errors"));
	}
	
	public static function processAvatar ($uid)
	{
		$imageProcessor = new image (200, 200);
		$expectedPics = array ("avatar"=>CONTENT_PATH."/avatars/$uid");
        $imageProcessor->handleAllUploads ($expectedPics);

        return $expectedPics;
	}

	public static function registerNewUser()
	{
		$input = array_map ("trim", $_POST);
		
        $password = md5(md5($input["password1"]));
        $email = htmlspecialchars($input["email"]);
        $nick = htmlspecialchars ($input["nick"]);
        
        self::$db->query ("INSERT INTO `users` SET `password`='?', `email`='?', `nick`='?', `source`='direct'", $password, $email, $nick);
		self::$smarty->assign ("successReg", "ok");
		system::registerEvent ("mail", "successUserReg", $email);
		$uid = self::$db->insert_id();

		if ($_FILES["avatar"]["error"]===0)
		{
			$expectedPics = self::processAvatar ($uid);

        	self::$db->query ("UPDATE `users` SET `avatar`='?', `avatar_small`='?' WHERE `userID`=?", $expectedPics["avatar"]["big"], 
        		$expectedPics["avatar"]["small"], $uid);
		}

		return array ($uid, trim ($_POST["password1"]));
	}
	
	public static function generateCode ($length=6) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;  
		
		while (strlen($code) < $length) 
		{
			$code .= $chars[mt_rand(0,$clen)];  
		}

		return $code;
	}
	
	public static function userLogin ($userID=false, $pass=false, $method="direct")
	{
		$userID = $userID ? intval ($userID) : trim ($_POST["email"]);
		$pass = trim ($pass ? $pass : $_POST["password"]);
		
		if (is_numeric ($userID))
			$data = self::$db->query ("SELECT `userID`, `password` FROM `users` WHERE `userID`=? AND `source`='?' LIMIT 1", $userID, $method);
		else if (is_string($userID)) {
			$data = self::$db->query ("SELECT `userID`, `password` FROM `users` WHERE `email`='?' AND `source`='?' LIMIT 1", $userID, $method);
		}

		if ($data->num_rows <= 0)
			return false;
		
		$data = $data->fetch();

		if ($data["password"] == md5(md5($pass)))
		{
			$hash = md5 (self::generateCode(10));
			$insip = isset ($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:'';

			self::$db->query ("UPDATE `users` SET `user_hash`='?', `ip`=INET_ATON('?') WHERE `userID`=?", $hash, $insip, $data["userID"]);

			if (isset ($_POST["rememberMe"]))
			{
				$time = time()+3600*24*365;
			} else $time = time()+3600*24*30;

			$domain = system::param ("siteDomain");
			setcookie ("id", $data["userID"], $time, '/', $domain);
			setcookie ("hash", $hash, $time, '/', $domain);
			system::$core->checkAuth ($data["userID"], $hash);
			
			return true;
		}
		
		return false;
	}
	
	public static function socialRegister ($method, $data)
	{
		$res = self::$db->query ("SELECT * FROM `users` WHERE `socID`=? AND `source`='?'", $data->identifier, $method);
		
		if ($res->num_rows <= 0)
		{		
			//$login = $data->displayName;
			$nick = trim ($data->firstName.' '.$data->lastName);
			
			if ($data->emailVerified)
				$email = $data->emailVerified;
			else if ($data->email)
				$email = $data->email;
			else $email = '';
			
			$insip = isset ($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:'';
			//var_dump ($data->photoURL);

			self::$db->query ("INSERT INTO `users` SET `socID`=?, `nick`='?', `password`=md5(md5('-')), `email`='?', `social_email`='?', `source`='?', 
				`ip`=INET_ATON('?'), `remote_pic`='?', `profileURL`='?'", 
				$data->identifier, $nick, $email, $email, $method, $insip, $data->photoURL, $data->profileURL);
			
			$userID = self::$db->insert_id();

			if ($data->photoURL)
			{
				$avatar = self::socialProcessAvatar ($data->photoURL, $userID);
				self::$db->query ("UPDATE `users` SET `avatar`='?', `avatar_small`='?' WHERE `userID`=?", $avatar["big"], $avatar["small"], $userID);
			}
			
			return $userID;
		} else {
			$user = $res->fetch();
			$sic = array();
			
			foreach ($data as $k=>$v)
			{
				if ($k == "email")
					continue;
				
				if (isset ($user[$k]) && $user[$k] != $v)
					$sic[$k] = $v;
			}
			
			if (isset ($sic["remote_pic"]))
			{
				$avatar = self::socialProcessAvatar ($sic["remote_pic"], $user["userID"]);
				$sic["avatar"] = $avatar["big"];
				$sic["avatar_small"] = $avatar["small"];
			}
			
			self::$db->updateTable ("users", $sic, "userID", $user["userID"]);
			
			return $user["userID"];
		}
	}
	
	public static function socialProcessAvatar ($photoURL, $userID)
	{
		$imageProcessor = new image (200, 200);
		$targetPath = CONTENT_PATH."/avatars";
		$tmpname = tempnam ($targetPath.'/', $userID);
		
		$ch = curl_init ($photoURL);
		$fp = fopen ($tmpname, "wb");
		curl_setopt ($ch, CURLOPT_FILE, $fp);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_exec ($ch);
		curl_close ($ch);
		fclose ($fp);
		
		$imgFormat = $imageProcessor->extendFormat ($tmpname);
		$avatar = $imageProcessor->processImage ($tmpname, $targetPath.'/'.$userID);
		
		return $avatar;	
	}
	
	public static function socialLogin ($method)
	{
		require (LIB_PATH."/hybridauth/Hybrid/Auth.php");
		$config = LIB_PATH."/hybridauth/config.php";
		$up = null;
		
		try  
		{
			$hybridauth = new Hybrid_Auth ($config);			
			$adapter = $hybridauth->authenticate ($method);
			$up = $adapter->getUserProfile();

		} catch( Exception $e ) {
			system::registerEvent("error", "socialAuthError", "Не удалось авторизоваться через $method", "$method ответил:\n".$e->getMessage());
		}

		return $up;
	}
	
	public static function logout()
	{
		unset ($_SESSION["user"]);
		$domain = system::param ("siteDomain");		 
		setcookie ("id", '', time() - 3600*24*365, '/', $domain);
		setcookie ("hash", '', time() - 3600*24*365, '/', $domain);		
	}
	
	public static function processPasswordRequest ()
	{
		system::checkFields (array ("email"=>"e-mail"));

		if (isset ($_POST["email"]))
		{
			$_POST = array_map ("trim", $_POST);
			
			if ($_POST["email"]==="0" || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
			{			
				system::registerEvent ("error", "email", "Адрес электронной почты введён не правильно.", "e-mail");
			}
			
			if (!system::checkErrors())
			{
				$usrChk = self::$db->query ("SELECT `email`,`userID`,`nick` FROM `users` WHERE `email`='?' AND `source`='direct' LIMIT 1", $_POST["email"]);
							
				if ($usrChk->num_rows == 0)
					system::registerEvent ("error", "email", "Пользователя с таким e-mail не существует.", "e-mail");
			}

			if (!system::checkErrors())
			{
				$code = self::generateCode(15);
				$userData = $usrChk->fetch();
				self::$db->query ("INSERT INTO `password_recovery` SET `userID`=?, `code`='?', `add_date`=NOW()", $userData["userID"], $code);
				self::$mail->assign ("code", $code);
				self::$mail->assign ("appeal", $userData["nick"]);
				system::registerEvent ("mail", "passwordRequest", $_POST["email"]);

				self::$smarty->assign ("emailForSend", $_POST["email"]);
				self::$mail->assign ("emailForSend", $_POST["email"]);
				self::$smarty->assign ("showPassDialog", true);

				return true;
			}


		}
	}

	public static function processRequestCode ($code)
	{
		self::$smarty->assign ("code", $code);
		$passwordReady = true;

		$findCode = self::$db->query ("SELECT * FROM `password_recovery` WHERE `code`='?' AND `add_date`>=DATE(DATE_SUB(NOW(), INTERVAL 3 DAY))", $code);

		if ($findCode->num_rows == 0)
		{
			$passwordReady = false;
			system::registerEvent ("error", "password1", "Ваш запрос не найден в системе или просрочен.", "Ошибка");
		}

		self::$smarty->assign ("passwordReady", $passwordReady);

		system::checkFields (array ("password1"=>"Пароль", "password2"=>"Проверочный пароль"));

		if (isset ($_POST["password1"]) && isset($_POST["password2"]) && 
				$_POST["password1"] != $_POST["password2"])
		{
			system::registerEvent ("error", "password2", "Пароли не совпадают.", "Пароль");
			system::registerEvent ("error", "password1", "Пароли не совпадают.", "Проверочный пароль");
		}

		if (isset ($_POST["password1"]) && isset($_POST["password2"]))
		{
			if (!system::checkErrors())
			{
				$user = $findCode->fetch();
				self::$db->query ("UPDATE `users` SET `password`=md5(md5('?')) WHERE `userID`=?", trim($_POST["password1"]), $user["userID"]);
				self::$db->query ("DELETE FROM `password_recovery` WHERE `id`=?", $user["id"]);
				system::redirect ('/', 5, "Ваш пароль успешно изменён.");
			}
		}
	}
}
