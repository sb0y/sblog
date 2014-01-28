<?php
class core
{
	public $smarty, $db, $mail;
	public $params = array(), $permMode = null, $errors = array();
	public $config = array ( "smartyConfig"=>array(), "dbConfig"=>array() );
	
	static public $router = null;
	static public $modulesLoader = null;
	public $configIsReaded = false;

	public function __construct()
	{
		//echo "core is constructed";
	}
	
	public function init()
	{
		$this->mail = new mail;
		$this->readConfig();
		$this->db = new db ( $this->config [ "dbConfig" ] );
		$this->smarty = new tpl ( $this->config [ "smartyConfig" ] );
		$this->smarty->assignByRef ( "errors", system::$errors );

		if ( system::$frontController == "backend" && self::$modulesLoader != null )
		{
            // get only active modules list
			$modules = self::$modulesLoader->getModulesListActive();
			$this->smarty->assign ( "adminModules", $modules );
		}

		$domain = system::param ( "siteDomain" );

		//session_name ( core::generateKey ( 5 ) . "_$domain" );
		session_set_cookie_params ( 0, '/', ( ".$domain" ) ); 
		session_start();

		$this->checkAuth();
		$this->countEmails();

		//echo "core is inited";
		return $this;
	}

	public function initDB()
	{
		$this->readConfig();
		$this->db = new db ( $this->config [ "dbConfig" ] );

		return $this;
	}
	
	public function initMail()
	{
		$this->mail = new mail;
		$this->readConfig();

		return $this;
	}

	public function loadModule ( $module )
	{
		if ( self::$modulesLoader == null )
			self::$modulesLoader = new modules_loader ( $this );
		
		return self::$modulesLoader->load ( $module );
	}

	public function initSmarty()
	{
		$this->readConfig();
		$this->smarty = new tpl ( $this->config [ "smartyConfig" ] );
	}

	public function readConfig()
	{
		if ( $this->configIsReaded )
			return true;

		$this->params = array();
		
		if ( !file_exists ( ROOT_PATH . "/config.php" ) )
			throw new exception ( "configReadError" );
		
		include ( ROOT_PATH . "/config.php" );
		
		if ( !isset ( $configuration ) )
			throw new exception ( "configIsEmpty" );
					
		$configParser = new config ( $this );
		$configParser->processArray ( $configuration );

		system::setParam ( "page", "main" );

		$this->configIsReaded = true;

		return true;
	}
	
	public function checkAuth ( $id = false, $hash = false )
	{
		if ( isset ( $_SESSION["user"] ) && $_SESSION["user"] )
			return;
				
		if ( $id )
			$_COOKIE["id"] = $id;
			
		if ( $hash )
			$_COOKIE["hash"] = $hash;
		
		if ( ( isset ( $_COOKIE["id"] ) && $_COOKIE["id"] ) && ( isset ( $_COOKIE["hash"] ) && $_COOKIE["hash"] ) )
		{
			$_COOKIE["id"] = intval ($_COOKIE["id"]);
			$_COOKIE["hash"] = preg_replace ("/[^a-z0-9]/i", '', $_COOKIE["hash"]);
			
			$userData = $this->db->query ( "SELECT *, INET_NTOA(`ip`) as ip FROM `users` WHERE `userID`=?", $_COOKIE["id"] )->fetch();

			if (($userData["user_hash"] != $_COOKIE["hash"]) || ($userData["userID"] != $_COOKIE["id"])
				 || (($userData["ip"] != $_SERVER["REMOTE_ADDR"])))
			{
				user::logout();
				//system::registerEvent ("error", "authError", "Неудалось авторизоваться", "Ошибка авторизации");
			} else {
				$_SESSION["user"] = $userData;
				$this->authSuccess = true;
				$_SESSION["user"]["mail"] = array ( "tms"=>0, "cnt"=>0 );	
				$this->countEmails();
			}
		}
		
	}

	public function countEmails()
	{
		if ( !isset ( $_SESSION["user"]["mail"] ) )
			return;

		$tms = time();

		if ( !$_SESSION["user"]["mail"]["tms"] || 
		   ( ( $tms - $_SESSION["user"]["mail"]["tms"] ) > 300 ) ) // каждые 5 минут проверить почту пользователя
		{
			$mcnt = $this->db->query ( "SELECT COUNT(*) as mcnt FROM `messages` WHERE `isRead`='N' AND `receiverID`=?", 
				$_SESSION["user"]["userID"] )->fetch();
		
			$_SESSION["user"]["mail"]["tms"] = $tms;
			$_SESSION["user"]["mail"]["cnt"] = $mcnt["mcnt"];	
		}

	}
	
	protected function handleMails()
	{
		$mails =& system::$emails;
		
		//print_r($mails);

		if ( empty ( $mails ) )
			return;
		
		//$vars = $this->smarty->getTemplateVars();

		foreach ( $mails as $k=>$v )
		{
			$file = TPL_PATH . "/mail/{$v[0]}.tpl";
			$this->mail->sendMail ( $file, $v[1] );  
		}
	}
	
	public function checkPermissions()
	{
		if ( system::$chmod & system::AUTH_REQUIRED )
		{
			if ( empty ( $_SESSION["user"] ) )
				$this->errors[] = "Для просмотра этой страницы необходимо авторизоваться.";
		}
		
		if ( system::$chmod & system::ADMIN_ONLY )
		{
			if ( !isset ( $_SESSION["user"] ) || $_SESSION["user"]["role"] != "admin" )
				$this->errors[] = "Эту страницу может просматривать только администратор.";
		}

		if ( !empty ( $this->errors ) )
		{
			return $this->errors;
		}

		return false;
	}

	public static function model ( $model )
	{
		return array ( $model, "options" => array( "isCoreModel" => true ) );
	}

	public static function pagination ( $allCount, $offset = 1 )
	{
		if ( $offset == 0 )
			$offset = 1;

		$pageCompose = new pagination ( $allCount );
		$pageCompose->setPerPage ( intval ( system::param ( "itemsOnPage" ) ) );
		$pageCompose->readInputData ( $offset );
		$mysqlLimits = $pageCompose->calculateOffset();
	
		$pages = $pageCompose->genPages();

		if ( count ( $pages ) > 1 )
		{
			system::$core->smarty->assign ( "pages", $pages );
			system::$core->smarty->assign ( "activePage", $offset );
		}

		return $mysqlLimits;
	}

	public static function generateSlug ( $str )
	{
		$str = core::transliterate ( $str );
		$str = strtolower ( $str );
		$str = preg_replace ( "/ +/s", "-", $str );
		$str = preg_replace ( "/[^_0-9a-z-]/s", '', $str );

		return addslashes ( $str );
	}

	public static function transliterate ( $str )
	{
		static $tbl;

		if ( !is_array ( $tbl ) )
		{
			$tbl = array (
				'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
				'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
				'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'A',
				'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G', 'З'=>'Z', 'И'=>'I',
				'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
				'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E', 'ё'=>"yo", 'х'=>"h",
				'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"", 'ь'=>"", 'ю'=>"yu", 'я'=>"ya",
				'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH", 'Ъ'=>"", 'Ь'=>"",
				'Ю'=>"YU", 'Я'=>"YA"
			);
		}

		return strtr ( $str, $tbl );
	}

	public static function rand ( $from = 0, $to = 15 )
	{
		list ( $usec, $sec ) = explode ( ' ', microtime() );
     	$rnd = (float) $sec + ((float) $usec * 100000);
     	srand ( $rnd );
     	return rand ( $from, $to );
	}

	public static function generateKey()
	{
		return time().'_'.self::rand(1,7);
	}
}
