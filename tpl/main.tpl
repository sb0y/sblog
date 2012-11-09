<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
    
    <meta charset="UTF-8" />
    <meta name="description" content="Уютный бложик программера" />
	<meta name="keywords" content="мудрые советы программирование инструкции how-to" />
	<link rel="image_src" href="{$urlBase}resource/images/logo-sb0y.png" />
    <meta name="author" content="Andrei Bagrintsev aka Sb0y">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="shortcut icon" href="{$urlBase}resource/images/favicon.ico" />
    <link rel="alternate" type="application/rss+xml" title="Bagrintsev blog" href="{$urlBase}rss/" />
    <link rel="apple-touch-icon" href="{$urlBase}resource/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{$urlBase}resource/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{$urlBase}resource/images/apple-touch-icon-114x114.png">

    <!--=== TITLE ===-->
    <title>{block name=title nocache}Bagrintsev Blog{/block}</title>

    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' id='droid-font-css'  href='http://fonts.googleapis.com/css?family=Droid+Serif%3A400%2C400italic%2C700&#038;ver=1' type='text/css' media='all' />
    <link rel='stylesheet' id='reset' href='{$urlBase}resource/css/reset.css' type='text/css' media='all' />
    <link rel='stylesheet' id='base-grid' href='{$urlBase}resource/css/grid.css' type='text/css' media='all' />
    <link rel="stylesheet" id="base-style" href="{$urlBase}resource/css/style.css" type="text/css" media="all" />
    <link rel='stylesheet' id='media-queries' href='{$urlBase}resource/css/media-queries.css' type='text/css' media='all' />
    <link rel="stylesheet"  href="{$urlBase}resource/css/auth-form.css" type="text/css" media="all" />
    <link rel="stylesheet"  href="{$urlBase}resource/css/standart-styles.css" type="text/css" media="all" />
	<link rel="stylesheet"  href="{$urlBase}resource/css/geshi.css" type="text/css" media="all" />
    
    <!--[if gte IE 8]><script src="{$urlBase}oldies/oldies.js" charset="utf-8"></script><![endif]-->
    <script type="text/javascript" src="{$urlBase}resource/js/css_browser_selector.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/scripts.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/popup.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/jquery.example.min.js"></script>

    <script type="text/javascript">
		var urlBase = "{$urlBase}";
    </script>{nocache}
    {block name=pageScripts nocache}{/block}
</head>
<body>
    <div id="wrapper" class="wrapper">
        <header>
            <div class="row">
                <div class="column_12 top-panel">
                    <ul class="float-right">
						{nocache}
						{if !empty ($smarty.session.user)}
                        <li><a href="{$urlBase}user/controlpanel">Вы вошли как &nbsp;<span class="strong{if $smarty.session.user.source!="direct"} {$smarty.session.user.source}{/if}">{if isset($smarty.session.user)}{$smarty.session.user.nick}{/if}</span></a></li>
                        <li><a href="{$urlBase}user/logout">Выход</a></li>
                        {else}
                        <li><a class="loginButton" href="javascript:;">Вход</a></li>
                        <li><a href="{$urlBase}user/registration">Регистрация</a></li> 
                        {/if}
                        {/nocache}
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="column_5 header">
                    <div class="logo">
                        <a href="{$urlBase}"><img alt="logo" src="{$urlBase}resource/images/logo.png" /></a>
                    </div>
                </div>
                <div class="header">
                    <div class="header-menu">
                        <ul class="float-right">
                            <li{if !isset ($args[0])} class="active"{/if} id="homepage"><a href="{$urlBase}">Главная</a></li>
                            <li{if $args[0]=="about"} class="active" {/if}><a href="{$urlBase}about">Обо мне</a></li>
                            <li class="has-dropdown"><a href="{$urlBase}blog">Блог</a>
                                {if isset ($categories)}<ul class="dropdown">
	                               {include file="includes/categories.tpl"}
                                </ul>{/if}
                            </li>
                            <li><a href="{$urlBase}portfolio">Портфолио</a></li>
                            <li{if $args[0]=="contacts"} class="active" {/if}><a href="{$urlBase}contacts">Контакты</a></li>
                        </ul>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="search-block float-right">
                <form method="get" action="{$urlBase}blog/search">
                    <input name="text" type="text" id="search"{if isset ($searchWord)} value="{$searchWord}" {/if}/><button type="submit">Найти</button>
                 </form>
            </div>
        </div>
        </header>
        <div class="clear"></div>
        {block name=body}
        <div class="posts-list">
        {foreach $posts as $key=>$item}
		{include file="includes/post4cycle.tpl"}
        {foreachelse}
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
        </div>
        {/block}
        {if isset ($pagination)}
        {include file="includes/pagination.tpl"}
        {/if}
        <footer>
            <div class="row">
				<div class="column_12 text-align-center font-size_11"> Powered by <strong>Sbl0g</strong></div>
                <div class="column_12 text-align-center font-size_11"> Copyright Andrei Bagrintsev aka Sb0y &copy; 2012</div>
                <div class="clear"></div>
            </div>
        </footer>
    </div></div>
</html>
