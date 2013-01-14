<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{block name=title nocache}Главная страница админки{/block}</title>
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/theme.css" />
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/style.css" />
<script type="text/javascript" src="/resource/js/css_browser_selector.js"></script>
<script type="text/javascript" src="/resource/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
var urlBase = "{$urlBase}";
</script>
<script type="text/javascript" src="/adm/resources/js/scripts.js"></script>
{*<!--[if IE]>
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/ie-sucks.css" />
<![endif]-->*}
{block name=pageScripts nocache}{/block}
</head>

<body>
	<div id="container">
    	<div id="header">
        	<h2>Админко</h2>
    <div id="topmenu">
            	<ul>
                	<li class="current"><a href="{$urlBase}blog/">Блог</a></li>
              </ul>
          </div>
      </div>
        <div id="top-panel">
            <div id="panel">
                <ul>
                    <li><a href="{$urlBase}blog/writePost" class="report">Написать новый пост</a></li>
                    <li><a href="{$urlBase}portfolio/addItem" class="report_seo">Добавить в портфолио</a></li>
                    {*<li><a href="#" class="search">Search</a></li>
                    <li><a href="#" class="feed">RSS Feed</a></li>*}
                </ul>
            </div>
		</div>
        <div id="wrapper">
            <div id="content">
                {if !empty($errors) nocache}
                    <h3>Не удалось выполнить запрошенную операцию</h3><br />
                    <div class="error-block">
                    <ul>
                        {foreach $errors as $k=>$v}
                        <li>{$v.txt}</li>
                        {/foreach}
                        </ul>
                    </div>
                {/if}
				{block name=body}
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                {/block}
            </div>

            <div id="sidebar">
				{block name=sidebar}
  				<ul>
                	<li><h3><a href="{$urlBase}blog/posts" class="house">Посты</a></h3>
                        <ul>
                        	<li><a href="{$urlBase}blog/posts" class="report">Список всех постов</a></li>
                    		<li><a href="{$urlBase}blog/postsWithComments" class="report_seo">Посты с комментариями</a></li>
                            {*<li><a href="#" class="search">Search</a></li>*}
                        </ul>
                    </li>
                    <li><h3><a href="$urlBase}portfolio" class="folder_table">Портфолио</a></h3>
						<ul>
                            <li><a href="{$urlBase}portfolio/addItem" class="addorder">Добавить объект</a></li>
							<li><a href="{$urlBase}portfolio" class="invoices">Все объекты портфолио</a></li>
							{*<li><a href="#" class="shipping">Invoices</a></li>*}
						</ul>
                    </li>
                   <li><h3><a href="#" class="manage">Настройки</a></h3>
          				<ul>
                            <li><a href="{$urlBase}blog/categories" class="manage_page">Категории</a></li>
                            <li><a href="{$urlBase}blog/addCat" class="promotions">Добавить новую категорию</a></li>
                            {*<li><a href="#" class="cart">Products</a></li>
                            <li><a href="#" class="folder">Product categories</a></li>*}
                        </ul>
                    </li>
                    <li><h3><a href="{$urlBase}users" class="user">Пользователи</a></h3>
          				<ul>
                            <li><a href="{$urlBase}users/add" class="useradd">Добавить пользователя</a></li>
                            <li><a href="{$urlBase}users" class="group">Список пользователей</a></li>
            				{*<li><a href="#" class="search">Find user</a></li>
                            <li><a href="#" class="online">Users online</a></li>*}
                        </ul>
                    </li>
				</ul>
				{/block}
          </div>
      </div>
</div>
</body>
</html>
