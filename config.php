<?php
$configuration = array (
	
	"smarty" => array (
		"tempateDir" => TPL_PATH,
		"compileDir" => TMP_PATH."/tpl_compiled",
		"cacheDir" => TMP_PATH."/cache",
		"configDir" => LIB_PATH."/smarty/config"
	),
	
	"db" => array (
		"database" => "database",
		"user" => "user",
		"password" => "secret", 
		"connect_host" => "localhost",
		"codepage" => "utf8"
	),

	"siteDomain" => "bagrintsev.me",

	"mail" => array(
		"codepage" => "UTF-8",
		"fromEmail" => "noreply@bagrintsev.me",
		"fromTitle" => "noreply@bagrintsev.me",
		"contentType" => "text/html"
	),

	"test" => "test"
);
