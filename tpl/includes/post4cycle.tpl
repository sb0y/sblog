<div class="post col-lg-13" id="post_{$item.contentID}">
	<h2><a href="{$urlBase}blog/{$item.slug|urlencode}">{$item.title}</a></h2>
                <ul class="nav nav-pills">
                    <li title="Дата написания статьи">
                        <a href="{$urlBase}blog/date/{$item.dt|date_format:"%d.%m.%Y"}"><span class="glyphicon glyphicon-time"></span>&nbsp;{$item.dt|date_format:"%d.%m.%Y"}</a>
                    </li>
                    <li title="Автор статьи"><a href="{$urlBase}user/profile/{$item.userID}"><span class="glyphicon glyphicon-user"></span>&nbsp;Sb0y</a></li>
                    {if count($item.cats) > 1}
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                          Категории <span class="caret"></span>
                        </a>
                    </li>
                    <ul class="dropdown-menu">
                    {foreach $item.cats as $k=>$v}
                        <li><a href="{$urlBase}blog/category/{$v.catSlug|urlencode}">{$v.catName}</a></li>
                    {/foreach}
                    </ul>
                    {elseif !empty ( $item.cats )}
                    {foreach $item.cats as $k=>$v}
                        {if $v@first}
                            <li title="Категории статьи"><a href="{$urlBase}blog/category/{$v.catSlug|urlencode}"><span class="glyphicon glyphicon-tag"></span>&nbsp;{$v.catName}</a></li>
                        {/if}
                    {/foreach}
                    {/if}
                </ul>

	<hr />
	<div class="content">
		<article>
			{if $item.short}{$item.short|nl2br}{else}{$item.body|nl2br}{/if}
		</article>
	</div>
	<div class="post-read-more">
		<a class="btn btn-primary" href="{$urlBase}blog/{$item.slug|urlencode}">Читать дальше</a>
	</div>
</div>
