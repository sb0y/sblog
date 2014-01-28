{block name=title}{if isset ($post)}{$post.title}{else}Ничего не найдено по запросу: "{basename ($routePath)}"{/if}{/block}
{block name=titleOg}{if isset ($post)}{$post.title}{/if}{/block}
{block name=imageMeta}{if $post.poster}{$urlBase}content/photo/200x200/{$post.poster}{/if}{/block}
{block name=imageSrc}{if $post.poster}{$urlBase}content/photo/original/{$post.poster}{/if}{/block}
{block name=pageDesc}{$post.short}{/block}
{block name=pageScripts}
    <script type="text/javascript" src="{$urlBase}resources/js/simpleEditor.js"></script>
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="{$urlBase}resources/js/fancyBox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="{$urlBase}resources/js/fancyBox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <link rel="stylesheet" href="{$urlBase}resources/js/fancyBox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <script type="text/javascript" src="{$urlBase}resources/js/fancyBox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/fancyBox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

    <link rel="stylesheet" href="{$urlBase}resources/js/fancyBox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <script type="text/javascript" src="{$urlBase}resources/js/fancyBox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
        $(".post_image").fancybox();
    </script>
    <script type="text/javascript" src="{$urlBase}resources/js/favorite.js"></script>
    <script type="text/javascript" src="{$urlBase}resources/js/commentRate.js"></script>
    <link rel="stylesheet" href="{$urlBase}resources/css/baloons.css" type="text/css" />
    <script type="text/javascript" src="{$urlBase}resources/js/baloons.js"></script>
{/block}
{block name=post_summary}
    {if isset ($post)}
<div class="offset_10 dark_gray horizontal_borders">
    <span>Дата:</span> <a href="{$urlBase}{$calledController}/date/{$post.dt|date_format:"%d.%m.%Y"}">{$post.dt|date_format:"%d.%m.%Y"}</a>,
    <span>Автор:</span> <a href="{$urlBase}user/profile/{$post.userID}">{$post.author}</a>,
    <span>Категори{if count ( $post.cats ) > 1 }и{else}я{/if}:</span> {if isset ($post.cats)}{foreach $post.cats as $k=>$v}<a href="{$urlBase}{$calledController}/category/{$v.catSlug|urlencode}">{$v.catName}</a>{if !$v@last},&nbsp;{/if}{/foreach}{/if}
    <a class="pull_right addToFavorite {$post.type}{if $isFav nocache} active{/if}" id="{$post.contentID}" href="javascript:;" title="Добавить в избранное">★</a>
</div>  
    {/if}
{/block}
{block name=body}

	{if isset ($post)}
            <article>
                <div class="row post" id="post_{$post.contentID}">
                	<div class="col span_12">
	                    <h1>{$post.title}</h1>
		                    	
	                    <div class="content">
                            {if $post.poster} {* пустая ли строка с данными о постере? *}
                                <a class="pull_left post_image" target="_blank" class="fancybox" href="{$urlBase}content/photo/resized/{$post.poster}"><img alt="post-image1" class="width-auto" src="{$urlBase}content/photo/200x200/{$post.poster}"/></a>
                            {/if}

							{$post.body}
	                    </div>
                        <p class="alert danger">
                            Перепечатка данного материала возможна при условии сохранения всех ссылок внутри текста и наличии активной и индексируемой ссылки на <b>9kg.me</b> как источник статьи.
                        </p>
                    </div>
                   
                </div>
            </article>
            <div class="comments">
                <ul class="comments">
                    {if isset ($comments)}
                    {include file="includes/comments.tpl"}
                    {/if}
                </ul>
                <form id="commentForm" method="post">
                    <div style="display:none;" id="quote_ID_placeholder"></div>
                    <div style="display:none;" id="reply_ID_placeholder"></div>
                    <input type="hidden" value="{$post.contentID}" name="contentID" />
                    {if isset ($smarty.session.user) nocache}
                    <div class="row borders_comment">
                        <div class="col span_1 user-info">
                            <a href="{$urlBase}user/profile/{$smarty.session.user.userID}" title="{$smarty.session.user.nick}" alt="{$smarty.session.user.nick}"><div class="avatar_block" style="background: url({if isset ($smarty.session.user.avatar_small)}{$urlBase}content/avatars/{$smarty.session.user.avatar_small}{else}{$urlBase}resources/images/no-avatar-small.png{/if}) no-repeat top center;"></div></a>
                            {if $smarty.session.user.group!="none"}<div class="groupIcon"><img src="{$urlBase}resources/images/group-icons/{$smarty.session.user.group}.gif" title="{$smarty.session.user.group} group" alt="{$smarty.session.user.group} group" /></div>{/if}
                        </div>
                        <div class="col span_11 post-data">
                            <div class="offset_10">
                                <p class="lh16"><a class="{if $smarty.session.user.source!="direct"} {$smarty.session.user.source}{/if}" href="{$urlBase}user/profile/{$smarty.session.user.userID}">{if isset($smarty.session.user)}{$smarty.session.user.nick}{/if}</a><a class="pull_right" href="{$urlBase}user/logout">Выход</a></p>
                                <div id="comment-textarea">
                                    <div class="panel_form" style="background: #eaecea; margin-top: 2px;" id="form_buttons">
                                        <a href="javascript:;" id="insertBold" class="button"><img src="{$urlBase}resources/images/text-icons/bold.png" width="20" height="20" title="жирный"></a>
                                        <a href="javascript:;" id="insertItalic" class="button"><img src="{$urlBase}resources/images/text-icons/italic.png" width="20" height="20" title="курсив"></a>
                                        <a href="javascript:;" id="insertUnderline" class="button"><img src="{$urlBase}resources/images/text-icons/underline.png" width="20" height="20" title="подчеркнутый"></a>
                                        <a href="javascript:;" id="insertStrike" class="button"><img src="{$urlBase}resources/images/text-icons/strike.png" width="20" height="20" title="зачеркнутый"></a>
                                        &nbsp;
                                        <a href="javascript:;" id="insertLink" class="button"><img src="{$urlBase}resources/images/text-icons/link.png" width="20" height="20" title="вставить ссылку"></a>
                                        <a href="javascript:;" id="insertBlockquote" class="button"><img src="{$urlBase}resources/images/text-icons/quote.png" width="20" height="20" title="цитировать выделенный текст"></a>
                                    </div>
                                    <textarea name="comment" class="comment-area" placeholder="Введите Ваше сообщение..." tabindex="4"></textarea>
                                    <div id="replyTo" class="date offset_5"></div>
                                    <div class="submit comment-submit">
                                        <button type="submit" class="submit btn info">Отправить</button>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                        
                    {else}
                        <p class="alert info"><a href="javascript:;" class="loginButton">Войдите</a> или <a target="_blank" href="{$urlBase}user/registration">зарегистрируйтесь</a>, чтобы написать комментарий.</p>
                    {/if}
                </form>
     </div>
     {else}
		<p>По вашему запросу ничего не найдено.</p>
     {/if}
{/block}
