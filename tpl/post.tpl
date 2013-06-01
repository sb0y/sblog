{extends file="main.tpl"}
{block name=title}{if isset ($post)}{$post.title}{else}Ничего не найдено по запросу: "{basename ($routePath)}"{/if}{/block}
{block name=pageScripts}
    <script src="{$urlBase}resource/js/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="{$urlBase}resource/js/weditor.js"></script>
{/block}
{block name=body}
<div class="posts-list post">
	{if isset ($post)}
            <article>
                <div class="row post" id="post_2">
                    <h1>{$post.title}</h1>
                    <div class="summary">
                        <span>Дата:</span> <a href="#">{$post.dt|date_format:"%d.%m.%Y"}</a>,
                        <span>Автор:</span> <a href="#">Sb0y</a>,
                        <span>Категории:</span> {if isset ($post.cats)}{foreach $post.cats as $k=>$v}<a href="{$urlBase}blog/category/{$v.catSlug|urlencode}">{$v.catName}</a>{if !$v@last},&nbsp;{/if}{/foreach}{/if}
                    </div>
                    {if isset ($post.img)}
                    <div class="post-image">
                        <img alt="post-image1" class="width-auto" src="{$urlBase}resource/images/post-image.png"/>
                    </div>
                    {/if}
                    <div class="content">
						{$post.body|nl2br}
                    </div>
                </div>
            </article>
            <div class="comments">
            {foreach $comments as $k=>$v}
                <div class="comment" id="comment_{$v.commentID}">
                   <div class="row">
                       <div class="user-info">
                           <p class="text-align-center padding-top-20 padding-left-20">{if isset ($v.avatar_small)}<img src="{$urlBase}content/avatars/{$v.avatar_small}" class="" title="{$v.nick}" alt="{$v.nick}" />{else}<img alt="Нет аватара" title="Нет аватара" src="{$urlBase}resource/images/no-avatar-small.png" class="" />{/if}</p>
                           <p class="text-align-center"><a{if $v.source!="direct"} class="{$v.source}" rel="nofollow" target="_blank"{/if} href="{if $v.source!="direct"}{$v.profileURL}{/if}">{$v.author}</a></p>
                        </div>
                        <div class="post-data">
                           <div class="padding-20 margin-left-50">
                                 {$v.dt|date_format:"%d"} {$v.dt|month_declination} в {$v.dt|date_format:"%H:%M"} <a href="{$urlBase}blog/{$post.slug|urlencode}/#comment_{$v.commentID}" title="Ссылка на комментарий">#</a>
                            </div>
                            <div class="padding-10 post-data"><!-- start post-data -->
                                {$v.body}
                             <!-- end post-data --></div>
                        </div>
                    </div>
                 </div>
                {foreachelse}
                    <p class="text-align-center padding-top-20">Статью пока никто не комментировал. Ваш комментарий может стать первым.</p>
                {/foreach}
                    {nocache}{if isset ($smarty.session.user)}
                        <div class="clear"></div>
                        <br />
                        <div id="comment-textarea">
                            <form method="post">{/nocache}
                                <input type="hidden" value="{$post.contentID}" name="contentID" />
                                {nocache}<textarea name="comment" class="comment-area" tabindex="4"></textarea>
                                <div class="submit comment-submit">
                                    <button type="submit" class="submit">Отправить</button>
                                </div>
                            </form>
                        </div>
                        {else}
                        <p class="text-align-center"><a href="javascript:;" class="loginButton">Войдите</a> или <a target="_blank" href="{$urlBase}user/registration">зарегистрируйтесь</a>, чтобы написать комментарий.</p>
                    {/if}{/nocache}
     </div></div>
     {else}
		<p>По вашему запросу ничего не найдено.</p>
     {/if}
{/block}
