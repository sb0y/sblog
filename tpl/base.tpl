<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="andrey@bagrintsev.me" />

    <title>bagrintsev.me : {block name=title}Andrey Bagrintsev`s Blog{/block}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{$urlBase}resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{$urlBase}resources/css/bootstrap-theme.min.css" rel="stylesheet" />    

    <link href="{$urlBase}resources/css/blog.css?v=4" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{$urlBase}resource/images/favicon.ico" />
    <link rel="alternate" type="application/rss+xml" title="Bagrintsev blog" href="{$urlBase}rss/" />
    <link rel="apple-touch-icon" href="{$urlBase}resource/images/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="{$urlBase}resource/images/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="{$urlBase}resource/images/apple-touch-icon-114x114.png" />

    <link rel="stylesheet" href="{$urlBase}resources/js/jGrowl/jquery.jgrowl.min.css" type="text/css" />
    <link rel="stylesheet" href="{$urlBase}resources/css/jgrowl-themes.css" type="text/css" />

    <!-- jQuery -->
    <script type="text/javascript" src="{$urlBase}resources/js/jquery-1.10.2.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="{$urlBase}resources/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="{$urlBase}resources/js/scripts.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/popup.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jGrowl/jquery.jgrowl.min.js"></script>

    <script type="text/javascript">
        var urlBase = "{$urlBase}";{nocache}
        var publicSession = {literal}{{/literal} {if !empty($smarty.session.user)}userID:{$smarty.session.user.userID},nick:'{$smarty.session.user.nick}'{/if} {literal}}{/literal};{/nocache}
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

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{$urlBase}">bagrintsev.me</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li{if $calledController=="about"} class="active"{/if}>
                        <a href="{$urlBase}about">Обо мне</a>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="services" aria-expanded="false">Сервисы <span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="download">
                            <li><a href="#">Тут пока пусто =)</a></li>
                        </ul>
                    </li>
                    <li{if $calledController=="contacts"} class="active"{/if}>
                        <a href="{$urlBase}contacts">Контакты</a>
                    </li>
                    <li{if $calledController=="server_status"} class="active"{/if}>
                        <a href="{$urlBase}server_status">Сервер</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right" role="menu">
                {if !isset ( $smarty.session.user ) nocache}
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">Авторизоваться <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="soc-ico"><a href="{$urlBase}user/login?to={$urlBase|urlencode}{$routePath|urlencode}">Войти</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="{$urlBase}user/login/through/vkontakte{if isset ( $routePath ) && $routePath}?to={$routePath|urlencode nocache}{/if}">
                                    <span class="vkontakte">Войти с ВКонтакте</span>
                                </a>
                            </li>
                            <li>
                                <a href="{$urlBase}user/login/through/facebook{if isset ( $routePath ) && $routePath}?to={$routePath|urlencode nocache}{/if}">
                                    <span class="facebook">Войти с Facebook</span>
                                </a>
                            </li>
                            <li>
                                <a href="{$urlBase}user/login/through/twitter{if isset ( $routePath ) && $routePath}?to={$routePath|urlencode nocache}{/if}">
                                    <span class="twitter">Войти с Twitter</span>
                                </a>
                            </li>
                            <li>
                                <a href="{$urlBase}user/login/through/google{if isset ( $routePath ) && $routePath}?to={$routePath|urlencode nocache}{/if}">
                                    <span class="google">Войти с G+</span>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="{$urlBase}user/registration">Регистрация</a></li>
                        </ul>
                </li>
                {else}
                <li class="dropdown">
                    <a href="javascript:;" style="padding:10px;" class="dropdown-toggle" data-toggle="dropdown">{if $smarty.session.user.avatar}<span class="userpic"><img class="img-responsive" src="{$urlBase}{$smarty.session.user|resolveAvatar}" /></span>{/if}{$smarty.session.user.nick} <span class="caret"></span></a>
                    <ul class="reset dropdown-menu" role="menu">
                        <li><a href="{$urlBase}user/controlpanel">Мой профиль</a></li>
                        <li>
                            <a href="{$urlBase}user/mail">Сообщения {if $smarty.session.user.mail.cnt}<span class="alert-danger badge">{$smarty.session.user.mail.cnt}</span>{/if}</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{$urlBase}user/logout">Выйти</a></li>
                    </ul>
                </li>
                {/if}
                </ul>

            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-md-8">
                {block name=body}{/block}

                {if isset ($pagination)}
                    {include file="includes/pagination.tpl"}
                {/if}
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Поиск</h4>

                    <form class="form-inline" method="get" action="{$urlBase}blog/search">
                        <div class="input-group">
                            <input name="text" type="text" class="form-control" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                               </button>
                            </span>
                        </div>
                    </form>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Категории</h4>
                            <ul class="list-unstyled">
                                {foreach $categories as $v}
                                <li><a href="{$urlBase}blog/category/{$v.catSlug}">{$v.catName}</a></li>
                                {/foreach}
                            </ul>
                    <!-- /.row -->
                </div>

                {*<!-- Side Widget Well -->
                <div class="well">
                    <h4>Side Widget Well</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
                </div>*}

            </div>

        </div>
        <!-- /.row -->

        <hr />

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                        <p>Powered by <strong><a href="https://github.com/sb0y/sblog" target="_blank" rel="nofollow">Sbl0g</a></strong></p>
                        <p>
                            Copyright {mailto address="andrey@bagrintsev.me" text="Andrey Bagrintsev aka Sb0y" encode="javascript_charcode"} &copy; 2012-{$smarty.now|date_format:"%Y"}
                        </p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

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
</body>

</html>