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
			system::registerEvent ( "error", "password2", "Пароли не совпадают", "Проверочный пароль" );
			system::registerEvent ( "error", "password1", "Пароли не совпадают", "Проверочный пароль" );
		}

		if (! empty ( $_POST["email"] ) )
		{
			if ( !filter_var ( $_POST["email"], FILTER_VALIDATE_EMAIL ) )
				system::registerEvent ("error", "email", "Адрес электронной почты введён не правильно", "e-mail");
			
			$usrChk = self::$db->query ( 
				"SELECT `email` FROM `users` WHERE `email`='?' AND `source`='direct'", $_POST [ "email" ] 
			);
			
			if ( $usrChk->num_rows > 0 )
				system::registerEvent ("error", "email", "Пользователь с таким e-mail уже существует", "e-mail");
		}
			
		self::updateSmarty();
	}
	
	public static function updateSmarty()
	{
		self::$smarty->assign ("errors", system::getEvents ("errors"));
	}
	
	public static function processAvatar ($uid)
	{
		$imageProcessor = new image ( 200, 200 );
		system::ensureDirectory ( CONTENT_PATH."/avatars/$uid" );
		$expectedPics = array ( "avatar" => CONTENT_PATH."/avatars/$uid" );
		$imageProcessor->handleAllUploads ( $expectedPics );

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

	public static function setCookie ( $id, $data, $time )
	{
		// доменное имя должно держать как минимум 2 точки
		// если сайти на локалхосте - кука не поставится
		// поэтому такой хак
		if ( $_SERVER [ "HTTP_HOST" ] == "localhost" )
		{
			$domain = false;
		} else {
			$domain = system::param ( "siteDomain" );
			$domain = ".www.$domain";
		}

		return setcookie ( $id, $data, $time, '/', $domain, false );
	}
	
	public static function userLogin ( $userID=false, $pass="", $method="direct" )
	{
		$userID = $userID ? intval ($userID) : trim ($_POST["email"]);
		$pass = trim ($pass ? $pass : $_POST["password"]);
		
		if (is_numeric ($userID))
			$data = self::$db->query ("SELECT `userID`, `password` FROM `users` WHERE `userID`=? AND `source`='?' LIMIT 1", $userID, $method);
		else if (is_string($userID)) {
			$data = self::$db->query ("SELECT `userID`, `password` FROM `users` WHERE `email`='?' AND `source`='?' LIMIT 1", $userID, $method);
		}

		if ( $data->num_rows <= 0 )
			return false;
		
		$data = $data->fetch();

		if ($data["password"] === md5(md5($pass)))
		{
			$hash = md5 (self::generateCode(10));
			$insip = isset ($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:'';

			self::$db->query ("UPDATE `users` SET `user_hash`='?', `ip`=INET_ATON('?') WHERE `userID`=?", $hash, $insip, $data["userID"]);

			if (isset ($_POST["rememberMe"]))
			{
				$time = time()+3600*24*365;
			} else $time = time()+3600*24*30;

			user::setCookie ( "id", $data [ "userID" ], $time );
			user::setCookie ( "hash", $hash, $time );

			system::$core->checkAuth ( $data["userID"], $hash );
			
			return true;
		}
		
		return false;
	}
	
	public static function socialRegister ($method, $data)
	{
		$res = self::$db->query ("SELECT * FROM `users` WHERE `socID`='?' AND `source`='?'", $data->identifier, $method);
		$nick = trim ( $data->firstName . ' ' . $data->lastName );

		if ($res->num_rows <= 0)
		{
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
			$dataArray = (array)$data;
			$dataArray["nick"] = $nick;
			$dataArray["remote_pic"] = $dataArray["photoURL"];

			foreach ($dataArray as $k=>$v)
			{
				if ($k == "email")
					continue;
				
				if (isset ($user[$k]) && $user[$k] != $v)
					$sic[$k] = $v;

				//if (isset ($user[$k]))
				//	echo $user[$k] ." = ". $v."\n";
			}
			
			if (isset ($sic["remote_pic"]))
			{
				$avatar = self::socialProcessAvatar ($sic["remote_pic"], $user["userID"]);
				$sic["avatar"] = $avatar["big"];
				$sic["avatar_small"] = $avatar["small"];
			}
			
			if ( !empty ( $sic ) )
			{
				self::$db->updateTable ("users", $sic, "userID", $user["userID"]);
				self::$smarty->clearCache ("userProfile.tpl");
				self::$smarty->clearBrowserCache();
			}

			return $user["userID"];
		}
	}
	
	public static function socialProcessAvatar ($photoURL, $userID)
	{
		$avatar = array ( "small"=>"", "big"=>"" );

		if ( !$photoURL )
			return $avatar;

		$imageProcessor = new image (200, 200);
		$targetPath = CONTENT_PATH."/avatars";
		system::ensureDirectory ( $targetPath );
		$tmpname = tempnam ($targetPath.'/', $userID);
		
		$ch = curl_init ($photoURL);

		if ( !$ch )
			return $avatar;

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
			//echo $e->getMessage();
			system::registerEvent("error", "socialAuthError", "Не удалось авторизоваться через $method", "$method ответил:\n".$e->getMessage());
		}

		return $up;
	}
	
	public static function logout()
	{
		if ( isset ( $_SESSION["user"] ) )
			unset ( $_SESSION["user"] );

		user::setCookie ( "id", "", -1 );
		user::setCookie ( "hash", "", -1 );
	}
	
	public static function processPasswordRequest()
	{
		system::checkFields ( array ( "email"=>"e-mail" ) );

		if ( isset ( $_GET [ "email" ] ) )
		{
			$email = trim ( urldecode ( $_GET [ "email" ] ) );
			$email = preg_replace ( "/[^a-zа-яё0-9\._\-@]/iu", '', $email );
			self::$smarty->assign ( "fill", array ( "email" => $email ) );
			$IP = system::getClientIP();
			
			if ( $email === "0" || 
				 !filter_var ( $email, FILTER_VALIDATE_EMAIL ) )
			{			
				system::registerEvent ("error", "email", "Адрес электронной почты введён не правильно.", "e-mail");
			}
			
			if ( !system::checkErrors() )
			{
				$usrChk = self::$db->query ("SELECT `email`,`userID`,`nick` FROM `users` WHERE `email`='?' AND `source`='direct' LIMIT 1", $email );
							
				if ( !$usrChk->getNumRows() )
					system::registerEvent ("error", "email", "Пользователя с таким e-mail не существует", "e-mail");
			}

			if ( !system::checkErrors() )
			{
				$retChk = self::$db->query ( 
				"SELECT `email` FROM `password_recovery` WHERE `add_date`>=(NOW() - INTERVAL 5 MINUTE) AND " .
				"`email`='?' AND `ip`='?'", $email, $IP );

				if ( $retChk->getNumRows() > 0 )
					system::registerEvent ( "error", "email", "Запрос на этот адрес отправлялся 5 минут назад. Подождите.", "e-mail" );
			}

			if ( !system::checkErrors() )
			{
				$code = self::generateCode ( 15 );
				$userData = $usrChk->fetch();
				
				self::$db->query ( "INSERT INTO `password_recovery` SET `userID`=?, `code`='?', `add_date`=NOW(), `ip`='?',`email`='?'", 
					$userData["userID"], $code, $IP, $email );

				self::$mail->assign ( "code", $code );
				self::$mail->assign ( "appeal", $userData [ "nick" ] );
				system::registerEvent ( "mail", "passwordRequest", $email );

				self::$smarty->assign ( "emailForSend", $email );
				self::$mail->assign ( "emailForSend", $email );
				self::$smarty->assign ( "showPassDialog", true );

				return true;
			}

			return false;
		}

		return false;
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

	public static function mailInbox ( $offset = 1 )
	{
		$userID = intval ( $_SESSION["user"]["userID"] );

		$_SESSION["user"]["mail"]["box"] = "inbox";

		$cntIn = self::$db->query ( "SELECT COUNT(*) as cnt FROM `messages` WHERE `receiverID`=?", $userID )->fetch();

		$cntOut = self::$db->query ( "SELECT COUNT(*) as cnt FROM `messages`,`users` WHERE `userID`=`senderID` AND `userID`=?", 
				$userID )->fetch();

		self::$smarty->assign ( "cntOut", $cntOut["cnt"] );
		self::$smarty->assign ( "cntIn", $cntIn["cnt"] );

		$limits = core::pagination ( $cntOut["cnt"], $offset );

		return self::$db->query ( "SELECT * FROM `messages` as m LEFT JOIN ".
			"`users` as u ON u.`userID`=m.`senderID` WHERE m.`receiverID`=? ORDER BY m.`dt` DESC LIMIT " . 
			implode ( ",", $limits ), $userID );
	}

	public static function mailOutbox ( $offset = 1 )
	{
		$userID = intval ( $_SESSION["user"]["userID"] );

		$_SESSION["user"]["mail"]["box"] = "outbox";

		$cntOut = self::$db->query ( "SELECT COUNT(*) as cnt FROM `messages`,`users` WHERE `userID`=`senderID` AND `userID`=?", 
				$userID )->fetch();

		$cntIn = self::$db->query ( "SELECT COUNT(*) as cnt FROM `messages` WHERE `receiverID`=?", $userID )->fetch();

		self::$smarty->assign ( "cntOut", $cntOut["cnt"] );
		self::$smarty->assign ( "cntIn", $cntIn["cnt"] );

		$limits = core::pagination ( $cntIn["cnt"], $offset );
		
		return self::$db->query ( "SELECT * FROM `messages` as m LEFT JOIN ".
			"`users` as u ON u.`userID`=m.`receiverID` WHERE m.`senderID`=? ORDER BY m.`dt` DESC LIMIT " . 
			implode ( ",", $limits ), $userID );
	}

	public static function sendUserMail ( array $receiverIDs, $senderID, $subject, $body )
	{
		if ( !$receiverIDs || !isset ( $_SESSION["user"] ) )
			return false;

		$receivers = array_map ( "intval", $receiverIDs );
		$subject = htmlspecialchars ( $subject );
		$body = htmlspecialchars ( $body );
		$senderID = intval ( $senderID );

		$isOk = true;
		$emailDataIDs = array();
		$senderMessagesIDs = array();
		$messageID = 0;

		for ( $i = 0; count ( $receivers ) > $i; ++$i )
		{
			if ( !self::$db->query ( "INSERT INTO `messages` (`senderID`,`nick`,`receiverID`,`body`,`subject`)".
				" VALUES (?,'?',?,'?','?')", 
				$senderID, $_SESSION["user"]["nick"], $receivers[$i], $body, $subject ) )
			{
				$isOk = false;
				break;
			} else {
				$messageID = self::$db->insert_id();
				$senderMessagesIDs[] = $messageID;
				self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_" . $receivers[$i] );
			}
		}

		$emailDataRes = self::$db->query ( "SELECT * FROM `users` WHERE `userID` IN (" . implode ( ",", $receivers ) . ")" );

		if ( $emailDataRes->getNumRows() )
		{
			$emailData = $emailDataRes->fetchAll();

			foreach ( $emailData as $k => $v )
			{
				if ( !$v["email"] )
				{
					if ( $senderMessagesIDs )
						array_shift ( $senderMessagesIDs );
					
					continue;
				}

				$messageID = 0;

				if ( $senderMessagesIDs )
					$messageID = array_shift ( $senderMessagesIDs );

				$v["data"] = array ( "senderID"=>$senderID, "subject"=>$subject, "body"=>$body, 
					"messageID"=>$messageID );

				self::$mail->assign ( "mail", $v );
				system::registerEvent ( "mail", "mailSendReport", $v["email"] );
			}
		}

		self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_$senderID" );

		if ( !$isOk )
			return false;

		return true;
	}

	public static function deleteMailMessages ( array $messages = array(), $readed = 0 )
	{
		if ( !$messages )
			return false;

		$messages = array_map ( "intval", $messages );
		
		if ( $readed )
		{
			user::mailIndicator ( $readed, '-' );
		}

		return self::$db->query ( "DELETE FROM `messages` WHERE `messageID` IN (" . implode ( ",", $messages ).")" );
	}

	public static function markMails ( array $array = array() )
	{
		if ( !$array )
			return false;


		$yes = $no = array();
		foreach ( $array as $k => $v )
		{
			if ( $v == "Y" )
				$yes[] = $k;
			else if ( $v == "N" )
				$no[] = $k;
		}

		$ret = false;

		if ( $yes )
		{
			user::mailIndicator ( $yes, '-' );
			$ret = self::$db->query ( "UPDATE `messages` SET `isRead`='Y' WHERE `messageID` IN (" . implode ( ",", $yes ) . ")" );
		}

		if ( $no )
		{
			user::mailIndicator ( $no, '+' );
			$ret = self::$db->query ( "UPDATE `messages` SET `isRead`='N' WHERE `messageID` IN (" . implode ( ",", $no ) . ")" );
		}

		return $ret;
	}

	public static function mailUserHistory ( $receiverID, $senderID )
	{
		$receiverID = intval ( $receiverID );
		$senderID = intval ( $senderID );

		// если понадобится cделать сложные перекрёстные выборки - не забыть про парные пересечения senderID и receiverID
		return self::$db->query ( "SELECT *, DATE_FORMAT (`dt`, '%d.%m.%y') as dtFormated, UNIX_TIMESTAMP (`dt`) as tms, " .
		"`receiverID` as userID, `senderID` as userID FROM `messages` WHERE " . 
		"(`receiverID`=$receiverID AND `senderID`=$senderID) OR (`senderID`=$receiverID AND `receiverID`=$senderID) " .
		"ORDER BY `dt` DESC" );
	}

	public static function mailIndicator ( $val, $sign = '-' )
	{
		switch ( $sign ) 
		{
			case '+':
				$_SESSION["user"]["mail"]["cnt"] += $val;
			break;
			
			case '-':
				$_SESSION["user"]["mail"]["cnt"] -= $val;

				if ( $_SESSION["user"]["mail"]["cnt"] < 0 )
					$_SESSION["user"]["mail"]["cnt"] = 0;

			break;
		}

		$_SESSION["user"]["mail"]["tms"] = time();
	}

	public static function getMailMessage ( $messageID, $userID )
	{
		$mail = self::$db->query ( "SELECT *, m.`dt` as mdt FROM `messages` as m, `users` as u WHERE " .
			"m.`messageID`=? AND IF (m.`senderID`=?, m.`receiverID`=u.`userID`, m.`senderID`=u.`userID`) " .
			" LIMIT 1", $messageID, $userID );

		if ( !$mail->getNumRows() )
		{
			return false;
		}

		$mdata = $mail->fetch();

		if ( $mdata["receiverID"] != $userID && $mdata["senderID"] != $userID )
		{
			return false;
		}

		if ( isset ( $_POST["messageID"] ) && $_POST["messageID"] )
		{
			if ( !system::checkFields ( array ( "replyToMessage"=>"replyToMessage" ) ) )
				return;

			$messageID = intval ( $_POST["messageID"] );
			$subject = "Без темы";
			$recs = array();
			$sendID = 0;
				
			if ( isset ( $_POST["subject"] ) && $_POST["subject"] )
				$subject = htmlspecialchars ( $_POST["subject"] );

			if ( !isset ( $_POST["receivers"] ) || !$_POST["receivers"] )
			{
				if ( $userID != $mdata["senderID"] )
				{
					$recs[] = $mdata["senderID"];
					$subject = "RE: " . $mdata["subject"];
				} else {
					$recs[] = $mdata["receiverID"];
				}
			}

			if ( user::sendUserMail ( $recs, $userID, $subject, $_POST["replyToMessage"] ) )
			{
				self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_$userID" );
				self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_" . $message["receiverID"] );
				return system::redirect ( "/user/mail" );
			}
		}

		if ( $userID != $mdata["senderID"] && $mdata["isRead"] == "N" )
		{
			self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_$userID|usermessage_$messageID" );
			self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_$userID" );
			self::$smarty->clearCache ( null, "USER|USERMAIL|usermail_" . $mdata["receiverID"] );
			
			self::$db->query ( "UPDATE `messages` SET `isRead`='Y' WHERE `messageID`=?", $messageID );

			if ( $_SESSION["user"]["mail"]["cnt"] )
			{
				user::mailIndicator ( 1, '-' );
			}
		}

		return $mdata;
	}

	public static function buildUserIDArray ( array &$array )
	{
		if ( !$array )
			return $array;

		$tmp = array();
		foreach ( $array as $k => $v )
		{
			$tmp [ $v["userID"] ] = $v;
		}
		
		return $tmp;
	}
}
