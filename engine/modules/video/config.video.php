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
		"nick" => "Видео",
		"description" => "Видео модуль Фдуща",
		"hasAdminInterface" => true,
		"menu" => array (
			"listPage" => "Список видео",
			"addPage" => "Добавить видео"
		)
	)
);
