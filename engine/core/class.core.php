<?php
class core
{
	public $smarty, $db, $mail;
	public $params = array(), $permMode = null, $errors = array();
	public $config = array ("smartyConfig"=>array(), "dbConfig"=>array());
	
	public function __construct()
	{
	}
	
	public function init()
	{
		$this->mail = new mail;
		$this->readConfig();
		$this->db = new db ($this->config["dbConfig"]);
		$this->smarty = new tpl ($this->config["smartyConfig"]);
		$this->checkAuth();
		$this->smarty->assignByRef ("errors", system::$errors);
	}
	
	public function initSmarty()
	{
		$this->readConfig();
		$this->smarty = new tpl ($this->config["smartyConfig"]);
	}

	public function readConfig()
	{
		$this->params = array();
		
		if (!file_exists (ROOT_PATH."/config.php"))
			throw new exception ("configReadError");
		
		include (ROOT_PATH."/config.php");
		
		if (!isset ($configuration))
			throw new exception ("configIsEmpty");
			
					
		$configParser = new config ($this);
		$configParser->processArray ($configuration);

		system::setParam ("page", "main");
	}
	
	public function checkAuth ($id=false, $hash=false)
	{
		if (!empty ($_SESSION["user"]))
			return;
				
		if ($id)
			$_COOKIE["id"] = $id;
			
		if ($hash)
			$_COOKIE["hash"] = $hash;
		
		if (isset($_COOKIE["id"]) && isset($_COOKIE["hash"]))
		{
			$_COOKIE["id"] = intval ($_COOKIE["id"]);
			$_COOKIE["hash"] = preg_replace ("/[^a-z0-9]/i", '', $_COOKIE["hash"]);
			
			$userData = $this->db->query ("SELECT *, INET_NTOA(`ip`) as ip FROM `users` WHERE `userID`=? LIMIT 1", $_COOKIE["id"])->fetch();

			if (($userData["user_hash"] != $_COOKIE["hash"]) || ($userData["userID"] != $_COOKIE["id"])
				 || (($userData["ip"] != $_SERVER["REMOTE_ADDR"])))
			{
				user::logout();
				system::registerEvent ("error", "authError", "Неудалось авторизоваться", "Ошибка авторизации");
			} else {
				$_SESSION["user"] = $userData;
				//$this->smarty->clearCache ("main.tpl");
				$this->authSuccess = true;
			}
		}
		
	}
	
	protected function handleMails()
	{
		$mails =& system::$emails;
		
		//print_r($mails);

		if (empty ($mails))
			return;
		
		//$vars = $this->smarty->getTemplateVars();

		foreach ($mails as $k=>$v)
		{
			$file = TPL_PATH."/mail/{$v[0]}.tpl";
			$this->mail->sendMail ($file, $v[1]);  
		}
	}
	
	public function checkPermissions()
	{
		if (system::$chmod & system::AUTH_REQUIRED)
		{
			if (empty ($_SESSION["user"]))
				$this->errors[] = "Для просмотра этой страницы необходимо авторизоваться.";
		}
		
		if (system::$chmod & system::ADMIN_ONLY)
		{
			if (!isset ($_SESSION["user"]) || $_SESSION["user"]["role"] != "admin")
				$this->errors[] = "Эту страницу может просматривать только администратор.";
		}

		if (!empty ($this->errors))
		{
			return $this->errors;
		}

		return false;
	}
}
