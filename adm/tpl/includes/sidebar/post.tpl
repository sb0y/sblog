<ul>
    <li><span>Посты</span></li>
	<li><a href="{$urlBase}blog/posts"  class="report {if $args[0] == 'posts' && $calledController=="news"}active{/if}">Список всех постов</a></li>
	<li><a href="{$urlBase}blog/postsWithComments" class="report_seo {if $args[0] == 'postsWithComments' && $calledController=="news"}active{/if}">Посты с комментариями</a></li>
	<li><a href="{$urlBase}blog/writePost" class="{if $args[0] == 'writePost' && $calledController=="news"}active{/if} report">Написать новый пост</a></li>
    <li><a href="{$urlBase}blog/categories" class="{if $args[0] == 'categories' && $calledController=="news"}active{/if} manage_page">Категории</a></li>
    <li><a href="{$urlBase}blog/addCat" class="{if $args[0] == 'addCat' && $calledController=="news"}active{/if} promotions">Добавить новую категорию</a></li>
</ul>
