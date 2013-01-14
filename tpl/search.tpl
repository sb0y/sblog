{extends file="main.tpl"}
{block name=title}Поиск по фразе "{$searchWord}"{/block}
{block name=body}
<div class="posts-list post">
<p><h2>Результаты поиска по сайту</h2></p>
{if isset ($smallWord)}
<p>Ваш запрос - слишком короткий. Пожайлуста, переформулируйте так, чтобы в нём было больше символов.</p>
{else}
{if isset ($searchRes)}
    {foreach $searchRes as $key=>$item}
       <article>
            <div class="row post" id="post_{cycle values="1,2"}">
                <h2>{$item.title}</h2>
                <div class="summary">
                    <span>Дата:</span> <a href="{$urlBase}blog/date/{$item.dt|date_format:"%d.%m.%Y"}">{$item.dt|date_format:"%d.%m.%Y"}</a>,
                    <span>Автор:</span> <a href="#">Sb0y</a>,
                    <span>Категории:</span> {foreach $item.cats as $k=>$v}<a href="{$urlBase}blog/category/{$v.catSlug|urlencode}">{$v.catName}</a>{if !$v@last},&nbsp;{/if}{/foreach}
                </div>
                <div class="content">
                    <p>{$item.short}</p>
                </div>
                <div class="post-read-more">
                    <a href="{$urlBase}blog/{$item.slug|urlencode}">Читать дальше</a>
                </div>
            </div>
        </article>
    {/foreach}
{else}
<p>По запросу "{$searchWord}" ничего не найдено.</p>
{/if}
{/if}
</div>
{/block}
