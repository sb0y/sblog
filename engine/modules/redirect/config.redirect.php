<?php
$moduleConfiguration = array (

	"routes" => array (
		"index" => array (
			"controller" => "index",
			"action" => "index",
			"model" => "defaultForThisControll"
		)
	),

	"info" => array (
		"nick" => "Перенаправление",
		"description" => "Модуль для редиректа с сайта",
		"hasAdminInterface" => true,
		"menu" => array (
			"listPage" => "Список редиректов",
			"add" => "Добавить редирект",
		)
	)
);