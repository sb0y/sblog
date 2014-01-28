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
		"nick" => "Статьи",
		"description" => "Заметки и статьи",
		"hasAdminInterface" => true,
		"menu" => array (
			"listPage" => "Список статей",
			"addPage" => "Добавить статью",
			"categories" => "Категории статей",
			"addCat" => "Добавить категорию статей"
		)
	)
);