<?php
$routeOptions = array (

	"default" => array (
		"controller" => "index",
		"action" => "index",
		"model" => "index"
	),

	"news" => array (
		"controller" => "news",
		"action" => "index",
		"model" => "defaultForThisControll"
	),

	"index" => array (
		"controller" => "index",
		"action" => "index",
		"model" => "defaultForThisControll"
	),

	"ajax" => array (
		"controller" => "ajax",
		"action" => "index",
		"model" => "defaultForThisControll"
	),

	"user" => array (
		"controller" => "user",
		"action" => "index",
		"model" => "defaultForThisControll"
	),

    "rss" => array (
        "controller" => "rss",
        "action" => "index",
        "model" => "defaultForThisControll"
    ),

    "search" => array (
		"controller" => "search",
		"action" => "index",
		"model" => "defaultForThisControll"
	),

	"video" => core::loadModule ( "video" ),
    "article" => core::loadModule ( "article" ),
    "photo" => core::loadModule ( "photo" ),
	"redirect" => core::loadModule ( "redirect" )
);
