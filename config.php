<?php
$configuration = array (
	
	"smarty" => array (
		"tempateDir" => TPL_PATH,
		"compileDir" => TMP_PATH."/tpl_compiled",
		"cacheDir" => TMP_PATH."/cache",
		"configDir" => LIB_PATH."/smarty/config"
	),
	
	"db" => array (
		"database" => "9kg",
		"user" => "9kg",
		"password" => "windows", 
		"connect_host" => "localhost",
		"codepage" => "utf8"
	),

	"mail" => array (
		"codepage" => "UTF-8",
		"fromEmail" => "noreply@9kg.me",
		"fromTitle" => "noreply@9kg.me",
		"contentType" => "text/html"
	),

	"settings" => array (
		"itemsOnPage" => 10,
		"optimizeHTML" => false,
		"siteDomain" => "dev.9kg.me"
	)
);
