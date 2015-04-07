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
		"nick" => "Скриншоты",
		"description" => "Модуль, реализующий хранение скриншотов из QScreenShotter",
		"hasAdminInterface" => true,
		"menu" => array (
			//"listPage" => "Список фотографий",
			//"addPage" => "Добавить фоточку"
		)
	)
);
