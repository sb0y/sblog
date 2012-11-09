<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{nocache}<title>{block name=title}Главная страница админки{/block}</title>{/nocache}
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/theme.css" />
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/style.css" />
<script type="text/javascript" src="/resource/js/css_browser_selector.js"></script>
<script type="text/javascript" src="/resource/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/adm/resources/js/scripts.js"></script>
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="{$urlBase}resources/css/ie-sucks.css" />
<![endif]-->
{nocache}{block name=pageScripts}{/block}{/nocache}
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
                    {*<li><a href="#" class="report_seo">SEO Report</a></li>
                    <li><a href="#" class="search">Search</a></li>
                    <li><a href="#" class="feed">RSS Feed</a></li>*}
                </ul>
            </div>
		</div>
        <div id="wrapper">
            <div id="content">
                {nocache}{if !empty($errors)}
                    <h3>Не удалось выполнить запрошенную операцию</h3><br />
                    <div class="error-block">
                    <ul>
                        {foreach $errors as $k=>$v}
                        <li>{$v.txt}</li>
                        {/foreach}
                        </ul>
                    </div>
                {/if}{/nocache}
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
                    {*<li><h3><a href="#" class="folder_table">Комментарии</a></h3>
						<ul>
							<li><a href="#" class="addorder">Последние комментарии</a></li>
							<li><a href="#" class="shipping">Shipments</a></li>
							<li><a href="#" class="invoices">Invoices</a></li>
						</ul>
                    </li>*}
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
