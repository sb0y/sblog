<?php
include (VKEXPORT_ROOT_PATH."/class.vklib.php");

define ( "scriptUrl", ( php_sapi_name() != "cli" ? 
	( (isset ( $_SERVER['HTTPS'] ) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"]) : "" ) );
define ( "DB", VKEXPORT_ROOT_PATH."/config.db" );

class vkExport
{
	const owner_id = -58178821;
	const GCODE = "945b7a3d57d251e464";
	const mode_cli = 0;
	const mode_server = 1;

	private $config = array(),
	$call = null,
	$status = 2, // 0 = critical error, cant continue; 1 = config file dosnt exist; 2 = configuration needed; 3 = all ok;
	$workMode = 0,
	$appConfig = array(),
	$attachments = array(),
	$isLinkAdded = false;

	function __construct ($mode=self::mode_cli)
	{
		$config['secret_key'] = "R1AeSu97PgCDEpf99s7w";
		$config['client_id'] = 2919631; // номер приложения
		//$config['user_id'] = 86253; // id текущего пользователя (не обязательно)
		//$config['access_token'] = '569cb60ca21a878ea3';
 		$config['scope'] = "wall,photos,video"; // права доступа к методам (для генерации токена)

 		$this->config = $config;
 		$this->workMode = $mode;
	}

	function __destruct()
	{

	}

	function init()
	{
		if ( !file_exists ( DB ) )
		{
			$this->status = 1;
		} else $this->readConfigForHumans();

		if ( !$this->status && !isset ( $this->appConfig["access_token"] ) )
			$this->status = 2;
		else $this->status = 3;

		switch ( $this->status )
		{
			case 1:
			case 2:
				$this->call = new Vk ( $this->config );
				$this->setup();
			break;
			case 3:
				$this->config["access_token"]  = $this->appConfig["access_token"];
				$this->config["user_id"]  = $this->appConfig["user_id"];
				$this->call = new Vk ( $this->config );
			break;
		}

	}

	function setup()
	{
		if ( !isset ( $this->appConfig["code_token"] ) )
		{
			 $url = $this->call->get_code_token();
   			 echo $url . "\n";

   			 $this->appConfig["code_token"] = "";
		}

		// здесь по идее надо сделать консольный ввод этого кода или вообще ходить за ним CURL'ом

		if ( isset ( $this->appConfig["code_token"] ) && $this->appConfig["code_token"] ) // код есть получаем токен доступа
		{
			$response = $this->call->get_token ( $this->appConfig["code_token"] );
			if ( !isset ( $response["error"] ) )
			{
				$this->appConfig["user_id"] = $response["user_id"];
				$this->appConfig["access_token"] = $response["access_token"];
			}
			//var_dump($response);
		}

		$this->writeConfigForHumans();
	}

	function writeConfigForHumans()
	{
		if ( ! count ( $this->appConfig ) )
			return print "Нечего записать в базу\n";

		$string = "";
		foreach ( $this->appConfig as $k => $v )
		{
			if ( empty ( $k ) )
				continue;

			$string .= $k . "=" . $v . "\n"; 
		}

		$n = file_put_contents ( DB, $string );

		if ( $n === false )
			echo "Не могу записать в файл. Проверьте права.\n";
	}

	function readConfigForHumans()
	{
		$string = file_get_contents ( DB );
		if ( $string === false )
		{
			echo "Ошибка при чтении файла.\n";
			return;
		}

		$array = explode ("\n", $string);
		$tmp = array();

		foreach ( $array as $part )
		{
			$tmp = explode ("=", $part);

			if ( !empty ( $tmp ) )
			{
				$this->appConfig [ $tmp[0] ] = isset ( $tmp[1] ) ? $tmp[1] : "";
			}
		}
	}

	function getToken()
	{
		return $this->config["access_token"];
	}

	function wallPost ($txt)
	{
		$qparams = array ( "message"=>$txt, "from_group"=>1, "owner_id"=>self::owner_id );

		if ( count ( $this->attachments ) > 0 )
		{
			$qparams["attachments"] = implode ( ',', $this->attachments );
		}

		$response = $this->call->api ( 'wall.post', $qparams );
		$this->attachments = array();
		$this->isLinkAdded = false;
		return $response;
	}

	function uploadPhotos ( $files = array() )
	{
		if ( empty ( $files ) )
		{
			echo "Массив с картинками не может быть пуст\n";
			return;
		}

		$res = $this->call->upload_photo (0, $files);

		if ( is_array ( $res ) )
			$this->attachments += $res;
	}

	function addLink ( $link )
	{
		if ( $this->isLinkAdded )
		{
			echo "Нельзя добавлять к посту больше одной ссылки\n \t -> \thttps://vk.com/dev/wall.post\n";
			return;
		}

		array_push ( $this->attachments, $link );
		$this->isLinkAdded = true;
	}

	function uploadVideo ( $file, $name = "", $desc = "" )
	{
		if ( !$file || !file_exists ($file) )
		{
			echo "Строка с файлом пуста или файл не найден\n";
			return;
		}

		$options = array (
			"wallpost" => 1,
			"group_id" => abs ( self::owner_id ),
			"name" => $name,
			"description" => $desc
		);

		$res = $this->call->upload_video ( $options, $file );

		if ( $res )
			array_push ( $this->attachments, $res ); 
	}

	function insertVideoFromYoutube ( $link, $name = "", $desc = "" )
	{
		  $options = array(
			"link" => $link,
			"title" => $name,
			"description" => $desc,
			"wallpost" => 1,
			"group_id" => abs ( self::owner_id )
   			);

		$res = $this->call->upload_video ( $options );

		if ( $res )
			array_push ( $this->attachments, $res ); 
	}
}