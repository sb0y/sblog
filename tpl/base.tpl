<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN"
    "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">
<html version="XHTML+RDFa 1.1" xmlns="http://www.w3.org/1999/xhtml" 
      xml:lang="en" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.w3.org/1999/xhtml
                          http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
>
<head>
    <title>9KG.me :: {block name=title}Главная страница{/block}</title>
    <meta name="author" content="9000 Games Team" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="{block name=pageDesc}Информационно-развлекательный портал{/block}" />
    <meta name="keywords" content="games игры обзоры новости" />
    <meta property="og:image" content="{block name=imageMeta}{$urlBase}resources/images/logo_news.jpg{/block}" />
    <meta property="og:title" content="9KG.me :: {block name=titleOg}Главная страница{/block}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{$urlBase}{$routePath}" />
    <meta property="fb:admins" content="100000320114032" />
    <meta property="og:site_name" content="9KG.me" />
    <meta property="og:description" content="Информационно-развлекательный портал 9KG.me" />
    <link rel="image_src" href="{block name=imageSrc}{$urlBase}resources/images/logo_news.jpg{/block}" />
    <link rel="shortcut icon" href="{$urlBase}favicon.ico" />
    <link rel="home" href="{$urlBase}" title="Home" />
    <link rel="stylesheet" href="{$urlBase}resources/css/reset.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/fonts/arimo.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/fonts/ubuntu.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/fonts/osans.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/css/layout.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/css/auth-form.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{$urlBase}resources/css/popup.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/js/jGrowl/jquery.jgrowl.min.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/css/jgrowl-themes.css" type="text/css" />
    <link rel="alternate" type="application/rss+xml" title="9000 Games RSS news feed" href="{$urlBase}rss/" />
    <!--[if lte IE 8]><script src="{$urlBase}oldies/oldies.js" charset="utf-8"></script><![endif]-->
    <script type="text/javascript" src="{$urlBase}resources/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/scripts.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/popup.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jquery.example.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jGrowl/jquery.jgrowl.min.js"></script>
    <script type="text/javascript">
        var urlBase = "{$urlBase}";{nocache}
        var publicSession = {literal}{{/literal} {if !empty($smarty.session.user)}userID:{$smarty.session.user.userID},nick:'{$smarty.session.user.nick}'{/if} {literal}}{/literal};{/nocache}
    </script>
    <script type="text/javascript" src="{$urlBase}resources/js/jquery.sticky.js"></script>
    <script type="text/javascript">
        $(window).load(function(){
            $(".panel").sticky({ topSpacing: 0 });
        });
    </script>
    {block name=pageScripts}{/block}
</head>
<body class="bg">
<div class="container">
<div class="border">

<!--  HEADER START  -->
<div class="head">
    <div class="row dark_gray horizontal_borders">
        <div class="col span_12 dark_gray shadow panel auth-box" style="width: 958px;">
            <ul class="nav pull_left">
                <li><a {if $calledController=="index"}class="active" {/if}href="{$urlBase}">Игры</a></li>
                <li><a {if $calledController=="news"}class="active" {/if}href="{$urlBase}news/category/news">Новости</a></li>
                <li><a {if $calledController=="article"}class="active" {/if}href="{$urlBase}article">Обзоры</a></li>
                <li><a {if $calledController=="video"}class="active" {/if}href="{$urlBase}video">Видео</a></li>
                <li><a href="#">Стрим</a></li>
                <li><a href="#">Faq</a></li>
            </ul>
            <ul class="nav pull_right user_navigation">
            {if !empty ($smarty.session.user) nocache}
                <li><a href="{$urlBase}user/logout">Выход</a></li>
            {else}
            	<li><a class="loginButton" href="javascript:;">Вход</a></li>
                <li><a href="{$urlBase}user/registration">Регистрация</a></li>	
            {/if}	
            </ul>
            <a class="to_top" href="#top"><div class="header_logo"></div>Наверх</a>
        </div>
    </div>
    <div class="row light_gray horizontal_borders">
        <div class="col span_6">
            <a href="{$urlBase}" class="header_logo pull_left"></a>
            {if !empty ($smarty.session.user) nocache}
            <div class="profile_block">
                <div class="pull_left">
                    <img class="userpic_small" src="{if isset ($smarty.session.user.avatar_small)}{$urlBase}content/avatars/{$smarty.session.user.avatar_small}{else}{$urlBase}resources/images/no-avatar-small.png{/if}" alt="profile" />
                </div>
                <div class="social_icon pull_left offset_0_5">
                    <span class="strong{if $smarty.session.user.source!="direct"} {$smarty.session.user.source}{/if}" style="padding: 0 15px 0 5px;"></span>
                </div>
                <div class="profile_info pull_left">
                    <ul>
                        <li><a href="{$urlBase}user/controlpanel">{$smarty.session.user.nick}</a></li>
                        <li><a id="mailCountHref" href="{$urlBase}user/mail">Личные сообщения{if $smarty.session.user.mail.cnt nocache} (<span id="mainCountHrefInt" class="strong">{$smarty.session.user.mail.cnt}</span>){/if}</a></li>
                        <li><a href="{$urlBase}user/comments">Комментарии</a></li>
                        <li><a href="{$urlBase}user/favorites">Избранное</a></li>
                    </ul>
                </div>
            </div>
            {/if}
        </div>
        <div class="col span_6">
            <div class="row">
                <div class="span_12">
                    <ul class="social pull_right">
                        <li><a target="_blank" href="https://vk.com/9kgames" class="btn dark"><i>B</i></a></li>
                        <li><a target="_blank" href="https://www.facebook.com/9kg.me" class="btn dark"><i>f</i></a></li>
                        <li><a target="_blank" href="https://twitter.com/9kg_me" class="btn dark"><i>t</i></a></li>
                        <li><a target="_blank" href="http://www.youtube.com/channel/UCWWwOOXiwlRwlIQW2pJdCTQ" class="btn dark"><i>▶</i></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="span_12">
                    <div class="search_block pull_right">
                    <form method="get" action="{$urlBase}{if $calledController!="index"}{$calledController}/{/if}search">
                        <input type="text" class="pull_left" id="search"  name="text" {* это уже делается средствами JS placeholder="Поиск"*} />
                        <button type="submit" class="pull_left btn dark"><i class="icon search"></i></button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if $smarty.template == 'main.tpl'}

    {block name=videos}{/block}
    {block name=homepage_title}{/block}

    {/if}
    {block name=post_summary}{/block}
</div>

<!--  HEADER END  -->

<!--  CONTENT HOT NEWS START  -->
    <div class="content_block">
    {if $smarty.template == 'main.tpl'}
    {block name=homepage}{/block}
    {else}
    <div class="offset_20">
    {block name=body}{/block}
    </div>
    {/if}
    </div>


<!--  CONTENT HOT NEWS END  -->
<!--  ADS BLOCK START  -->

<div class="row light_gray horizontal_borders">
    <div class="col span_4">
        <div class="block center offset_10">
            <a href="#" class="ad_3 payday2"><img src="{$urlBase}resources/images/payday.png" /></a>
        </div>
    </div>
    <div class="col span_4">
        <div class="block center offset_10">
            <a href="#" class="ad_3 deadisland2"><img src="{$urlBase}resources/images/deadisland.png" /></a>
        </div>
    </div>
    <div class="col span_4">
        <div class="block center offset_10">
            <a href="#" class="ad_3 battlefield3_9kg"><img src="{$urlBase}resources/images/batlefield3_9kg.png" /></a>
        </div>
    </div>
</div>

<!--  ADS BLOCK END  -->


<!-- FOOTER START -->
<div class="row head dark_gray horizontal_borders">
        <div class="col span_12 dark_gray shadow">
            <ul class="nav pull_left">
                <li><a {if $calledController=="index"}class="active" {/if}href="{$urlBase}">Игры</a></li>
                <li><a {if $calledController=="news"}class="active" {/if}href="{$urlBase}news/category/news">Новости</a></li>
                <li><a {if $calledController=="article"}class="active" {/if}href="{$urlBase}article">Обзоры</a></li>
                <li><a {if $calledController=="video"}class="active" {/if}href="{$urlBase}video">Видео</a></li>
                <li><a href="#">Стрим</a></li>
                <li><a href="#">Faq</a></li>
                <li><a {if $calledController=="about"}class="active" {/if}href="{$urlBase}about">О нас</a></li>
                <li><a {if $calledController=="contacts"}class="active" {/if}href="{$urlBase}contacts">Контакты</a></li>
            </ul>
        </div>
    </div>
<div class="row soft_dark_gray horizontal_borders">
    <div class="block offset_20 footer_content">
        <div class="col span_4">
            <p>© 2013 9kg.me</p>
            <p>Использование любых материалов сайта без согласования с администрацией запрещено.</p>
        </div>
        <div class="col span_4">
        </div>
        <div class="col span_4">
            <p>Тут может быть ваша реклама, а еще счетчики и вообще.</p>
        </div>
    </div>
</div>
</div>
<!-- FOOTER END -->

</div>
{literal}
<!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter22901080 = new Ya.Metrika({id:22901080, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/22901080" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
<!-- GA counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46703465-1', '9kg.me');
  ga('require', 'linkid', 'linkid.js');
  ga('send', 'pageview');
</script>
<!-- GA counter -->
{/literal}
</body>
</html>