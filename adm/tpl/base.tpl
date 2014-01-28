<!DOCTYPE html>
<html>
<head>
    <title>9000 Games</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="home" href="{$urlBase}" title="Home" >
    <link rel="stylesheet" href="{$urlBase}resources/css/reset.css" type="text/css">
    <link rel="stylesheet" href="{$urlBase}resources/css/pikaday.css" type="text/css">
    <link rel="stylesheet" href="{$urlBase}resources/css/layout_back.css" type="text/css">
    <link rel="stylesheet" href="/resources/css/popup.css" type="text/css" />
    <script type="text/javascript" src="/resources/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jquery.sticky.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jquery.form.js"></script>
    <link rel="stylesheet" type="text/css" href="/resources/css/imgareaselect-default.css" />
    <script type="text/javascript" src="/resources/js/jquery.imgareaselect.min.js"></script>
    
    <script type="text/javascript">
        var urlBase = "{$urlBase}";

        $(window).load(function(){
            $(".header").sticky({ topSpacing: 0 });
        });

    </script>
    {block name=pageScripts}{/block}
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col span_3 column dark">
            <div class="block ">
                <div class="main_menu">
                    <ul>
                        <li><a href="#"><i class="icon search"></i></a></li>
                        <li><a href="{$urlBase}users" class="{if $calledController == "users"}active{/if}"><i class="icon user"></i></a></li>
                        <li><a href="{$urlBase}news/posts"  class="{if $calledController == "news"}active{/if}"><i class="icon news"></i></a></li>
                        <li><a href="#"><i class="icon photo"></i></a></li>
                        <li><a href="#"><i class="icon video"></i></a></li>
                        <li><a href="#"><i class="icon settings"></i></a></li>
                    </ul>
                </div>
                <div class="sub_navigation">
                    <div class="offset_left_80">
                        <h1>Admin panel</h1>
                        {block name=sub_sidebar nocache}{include file="includes/sidebar/post.tpl"}{/block}
                        <h1>Модули</h1>
                        <ul>
                        {foreach $adminModules as $module}
                            <li><span title="{$module.description}">{$module.nick}</span></li>
                            {foreach $module.menu as $key=>$item}
                                <li><a href="{$urlBase}{$module.index}/{$key}" class="{if $args[0] == $key && $calledController==$module.index}active{/if}">{$item}</a></li>
                            {/foreach}
                        {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col span_12 ">

        <!-- LIST VIEW  -->
            <div class="block white offset_left_320">
				{if !empty($errors) nocache}
                    <div class="error-block">
                    <script type="text/javascript">
                    $(document).ready(function(){
						setTimeout('$(".error-block").fadeOut(300)', 5000);
					});
                    </script>
                    <h3>Не удалось выполнить запрошенную операцию</h3><br />
                    <ul>
                        {foreach $errors as $k=>$v}
                        <li>{$v.txt}</li>
                        {/foreach}
                        </ul>
                    </div>
                {/if}
                {block name=body nocache}{/block}
            </div>
        </div>
    </div>
</div>
</body>
</html>