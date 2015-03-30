<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>
    <link rel="stylesheet" href="{$urlBaseAdm}resources/css/pikaday.css" type="text/css">
    <link rel="stylesheet" href="{$urlBaseAdm}resources/css/popup.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="{$urlBaseAdm}resources/css/imgareaselect-default.css">

    <!-- jQuery -->
    <script type="text/javascript" src="{$urlBase}resources/js/jquery-1.10.2.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <link href="{$urlBase}resources/css/bootstrap.min.css" rel="stylesheet">
    <link href="{$urlBaseAdm}resources/css/dashboard.css" rel="stylesheet">
	<link href="{$urlBaseAdm}resources/css/style.css" rel="stylesheet">    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="{$urlBase}resources/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/scripts.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/popup.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/jGrowl/jquery.jgrowl.min.js"></script>
    <script type="text/javascript" src="{$urlBaseAdm}resources/js/jquery.form.js"></script>
    <script type="text/javascript" src="{$urlBaseAdm}resources/js/jquery.imgareaselect.min.js"></script>

    <script type="text/javascript">
        var urlBase = "{$urlBase}";
        var urlBaseAdm = "{$urlBaseAdm}";
    </script>


    {block name=pageScripts}{/block}
</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
 		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Project name</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li{if $calledController == "index"} class="active"{/if}><a href="{$urlBaseAdm}">Overview <span class="sr-only">(current)</span></a></li>
				<li{if $calledController == "users"} class="active"{/if}><a href="{$urlBaseAdm}users">Пользователи</a></li>
				<li{if $calledController == "blog"} class="active"{/if}><a href="{$urlBaseAdm}blog/posts">Посты</a></li>
				{if $adminModules}
				<li class="dropdown closed">
					<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;" id="services" aria-expanded="true">Ещё <span class="caret"></span></a>
						<ul class="dropdown-menu" aria-labelledby="download">
							{foreach $adminModules as $module}
							<li role="presentation" class="dropdown-header">
								<span title="{$module.description}">{$module.nick}</span>
							</li>
							{foreach $module.menu as $key=>$item}
							<li>
								<a href="{$urlBase}{$module.index}/{$key}" class="{if $args[0] == $key && $calledController==$module.index}active{/if}">{$item}</a>
							</li>
							{/foreach}
							{/foreach}
						</ul>
				</li>
				{/if}
			</ul>
			{*<form class="navbar-form navbar-right">
				<input type="text" class="form-control" placeholder="Search...">
			</form>*}
		</div>
	</div>
</nav>


<div class="container-fluid">
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li{if $calledController == "index"} class="active"{/if}><a href="{$urlBaseAdm}">Overview <span class="sr-only">(current)</span></a></li>
				<li{if $calledController == "users"} class="active"{/if}><a href="{$urlBaseAdm}users">Пользователи</a></li>
				<li{if $calledController == "blog"} class="active"{/if}><a href="{$urlBaseAdm}blog/posts">Посты</a></li>
			</ul>
			{if $adminModules}
			<ul class="nav nav-sidebar">
				{foreach $adminModules as $module}
				<li class>
					<span title="{$module.description}">{$module.nick}</span>
				</li>
				{foreach $module.menu as $key=>$item}
				<li>
					<a href="{$urlBase}{$module.index}/{$key}" class="{if $args[0] == $key && $calledController==$module.index}active{/if}">{$item}</a>
				</li>
				{/foreach}
				{/foreach}
			</ul>
			{/if}
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			{if !empty($errors) nocache}
			<div class="panel panel-danger">
				<div class="panel-heading">
					Не удалось выполнить запрошенную операцию(ии)
				</div>
				<div class="panel-body">
					<ul>
						{foreach $errors as $k=>$v}
						<li>{$v.txt}</li>
						{/foreach}
					</ul>
				</div>
			</div>
			<br>
			{/if}
			{block name=body nocache}{/block}
		</div>
	</div>
