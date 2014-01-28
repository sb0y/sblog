#!/usr/bin/php
<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
define ("VKEXPORT_ROOT_PATH", dirname(__FILE__));
require ( VKEXPORT_ROOT_PATH."/class.webexport.php" );
define ("ROOT_PATH", VKEXPORT_ROOT_PATH."/..");
require ( ROOT_PATH."/engine/core/init.php" );

// инициализация движка
$engine = system::init ( "script" );

if ( !$engine )
	exit ( "Ошибка при инициализации движка\n" );

$content = $engine->db->query ( "SELECT *, c.`type`,c.`slug`,c.`dt` FROM `content` as c LEFT JOIN `export_log` as e ".
"USING (`contentID`) WHERE c.`showOnSite`='Y' AND NOT EXISTS (SELECT `contentID` FROM `export_log` WHERE ".
"`contentID`=c.`contentID`) ORDER BY c.`dt` ASC");

if ( !$content->getNumRows() )
	exit ( 0 );

$content->runAfterFetchAll[] = array ( "news", "makeSlug" );

// инициализация скрипта для экспорта
$vk = new vkExport ( vkExport::mode_cli );
$vk->init();

$array = $content->fetchAll(); // забираем в память скрипта данные

$domain = system::param ( "siteDomain" );
$module = "NULL";
// начинаем методично срать в социалочку и делаем зарубки в журнале по поводу насранного
foreach ( $array as $k => $v )
{
	// обязательно добавим красивую ссылку к посту
	$vk->addLink ( "http://$domain/" . $v["URL"] );
	$res = $vk->wallPost ( strip_tags ( $v["short"], '' ) );
	// если всё ок, сообщим базе данных, что этот контент уже загржен в паблик
	if ( isset ( $res["post_id"] ) && $res["post_id"] )
	{
		if ( $v["type"] != "news" && $v["type"] != "blog" )
			$module = $v["type"];

		$engine->db->query ( "INSERT INTO `export_log` (`contentID`,`slug`,`type`,`module`,`social_type`) VALUES ".
			"(?,'?','?','?','?')", $v["contentID"], $v["slug"], $v["type"], $module, "vkontakte" );
		
		$mail = $engine->db->query ( "SELECT * FROM `users` as u, `content` as c WHERE c.`userID`=u.`userID` AND c.`contentID`=?",
			$v["contentID"] )->fetch();

		if ( $mail && $mail["email"] )
		{
			$mail["domain"] = $domain;
			$mail["post_id"] = $res["post_id"];
			$engine->mail->assign ( "mail", $mail );
			system::registerEvent ( "mail", "vkExportComplete", $mail["email"] );
		}

		sleep ( 5 );
	} else {
		print_r ( $res );
		exit ( 0 );
	}

	$module = "NULL";
	unset ( $array[$k] ); // избавляемся от говна по мере необходимости и экономим память процесса.
}

//$obj->uploadPhotos ( array ( VKEXPORT_ROOT_PATH."/test2.jpg", VKEXPORT_ROOT_PATH."/test3.jpg" ) );
//$obj->uploadVideo ( VKEXPORT_ROOT_PATH."/test.mp4", "тестовый видосик c youtube", "описание к тестовому видосику c youtube" );
//$obj->insertVideoFromYoutube ( "http://www.youtube.com/watch?v=FcMRkyoHKeA", "тестовый видосик c youtube", "описание к тестовому видосику c youtube" );
//$obj->addLink ( "http://9kg.me" );

//print_r ( $obj->wallPost ("пост с видосиком c youtube") );
