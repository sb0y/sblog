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
    <title>{block name=title}Bagrintsev Blog{/block}</title>

    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' id='droid-font-css'  href='http://fonts.googleapis.com/css?family=Droid+Serif%3A400%2C400italic%2C700&#038;ver=1' type='text/css' media='all' />
    <link rel='stylesheet' id='reset' href='{$urlBase}resource/css/reset.css' type='text/css' media='all' />
    <link rel='stylesheet' id='base-grid' href='{$urlBase}resource/css/grid.css' type='text/css' media='all' />
    <link rel="stylesheet" id="base-style" href="{$urlBase}resource/css/style.css" type="text/css" media="all" />
    <link rel='stylesheet' id='media-queries' href='{$urlBase}resource/css/media-queries.css' type='text/css' media='all' />
    <link rel="stylesheet"  href="{$urlBase}resource/css/auth-form.css" type="text/css" media="all" />
    <link rel="stylesheet"  href="{$urlBase}resource/css/standart-styles.css" type="text/css" media="all" />
	<link rel="stylesheet"  href="{$urlBase}resource/css/geshi.css" type="text/css" media="all" />
    
    <!--[if lte IE 8]><script src="{$urlBase}oldies/oldies.js" charset="utf-8"></script><![endif]-->
    <script type="text/javascript" src="{$urlBase}resource/js/css_browser_selector.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/scripts.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/popup.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/jquery.example.min.js"></script>

    <script type="text/javascript">
		var urlBase = "{$urlBase}";
    </script>
    {block name=pageScripts nocache}{/block}
    {literal}
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-3086718-3', '.bagrintsev.me');
        ga('create', 'UA-3086718-3', '.bagrincev.ru');
        ga('send', 'pageview');
    </script>
    {/literal}
</head>
<body>
    <div id="wrapper" class="wrapper">
        <header>
            <div class="row">
                <div class="column_12 top-panel">
                    <ul class="float-right">
						{if !empty ($smarty.session.user) nocache}
                        <li><a href="{$urlBase}user/controlpanel">Вы вошли как &nbsp;<span class="strong{if $smarty.session.user.source!="direct"} {$smarty.session.user.source}{/if}">{if isset($smarty.session.user)}{$smarty.session.user.nick}{/if}</span></a></li>
                        <li><a href="{$urlBase}user/logout">Выход</a></li>
                        {else}
                        <li><a class="loginButton" href="javascript:;">Вход</a></li>
                        <li><a href="{$urlBase}user/registration">Регистрация</a></li> 
                        {/if}
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
                            <li{if isset ($args[0]) && $args[0]=="about"} class="active" {/if}><a href="{$urlBase}about">Обо мне</a></li>
                            <li class="has-dropdown"><a href="{$urlBase}blog">Блог</a>
                                {if isset ($categories)}<ul class="dropdown">
	                               {include file="includes/categories.tpl"}
                                </ul>{/if}
                            </li>
                            <li{if isset ($args[0]) && $args[0]=="portfolio"} class="active" {/if}><a href="{$urlBase}portfolio">Портфолио</a></li>
                            <li{if isset ($args[0]) && $args[0]=="contacts"} class="active" {/if}><a href="{$urlBase}contacts">Контакты</a></li>
							<li{if isset ($args[0]) && $args[0]=="server_status"} class="active" {/if}><a href="{$urlBase}server_status">Сервер</a></li>
                        </ul>
                    </div>
                </div>
        </div>
        <div class="row">
            <div class="search-block float-right">
                <form method="get" action="{$urlBase}blog/search">
                    <input name="text" type="text" id="search"{if isset ($searchWord) nocache} value="{$searchWord}" {/if}/><button type="submit">Найти</button>
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
				<div class="column_12 text-align-center font-size_11"> Powered by <strong><a href="https://github.com/sb0y/sblog" target="_blank" rel="nofollow">Sbl0g</a></strong></div>
                <div class="column_12 text-align-center font-size_11"> Copyright Andrei Bagrintsev aka Sb0y &copy; 2012-{$smarty.now|date_format:"%Y"}</div>
                <div class="clear"></div>
            </div>
            {literal}
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter18757720 = new Ya.Metrika({id:18757720,
                                webvisor:true,
                                clickmap:true,
                                accurateTrackBounce:true});
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
            </script>
            <noscript><div><img src="//mc.yandex.ru/watch/18757720" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
            {/literal}
        </footer>
    </div></div>
</html>
