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
		"nick" => "Фото",
		"description" => "Фото модуль Фдуща",
		"hasAdminInterface" => true,
		"menu" => array (
			"listPage" => "Список фотографий",
			"addPage" => "Добавить фоточку"
		)
	)
);
