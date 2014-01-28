{foreach $comments as $k=>$v}
<li class="comment {cycle values="odd,even"}" id="comment_{$v.commentID}" data-userid="{$v.userID}" data-id="{$v.commentID}">
    <div class="row">
        <div class="col span_1 user-info">
            <a id="authorNickHref" href="#" title="{$v.nick}" alt="{$v.nick}"><div class="avatar_block" style="background: url({if isset ($v.avatar_small)}{$urlBase}content/avatars/{$v.avatar_small}{else}{$urlBase}resource/images/no-avatar-small.png{/if}) no-repeat top center; "></div></a>
            {if $v.group!="none"}<div class="groupIcon"><img src="{$urlBase}resources/images/group-icons/{$v.group}.gif" title="{$v.group} group" alt="{$v.group} group" /></div>{/if}
        </div>
        <div class="col span_11 post-data">
            <div class="offset_10">
                <p class="lh16">
                    <a class="{$v.source} authorName" href="{$urlBase}user/profile/{$v.userID}">{$v.author}</a>
                    <span class="pull_right date">{$v.dt|date_format:"%d"} {$v.dt|month_declination} в {$v.dt|date_format:"%H:%M"} <a href="{$urlBase}{$routePath}/#comment_{$v.commentID}" title="Ссылка на комментарий">#</a>
                    <a class="replyAction" data-userid="{$v.userID}" href="javascript:;" title="Ответить">Ответить</a>
                    <a class="quoteThisComment" data-userid="{$v.userID}" href="javascript:;" title="Цитировать">Цитировать</a>
                    <a class="ratingDown rating" href="javascript:;" title="Опустить рейтинг комментария">↓</a>
                    <span class="baloonRating ratingCounter rating comment_{$v.commentID}" title="Рейтинг комментария" data-id="{$v.commentID}">{$v.rate}</span>
                    <a class="ratingUp rating" href="javascript:;" title="Поднять рейтинг комментария">↑</a>
                    </span>
                </p>
                <div id="textbody" class="offset_10 replyAction">
                    <div class="commentText">{$v.body|nl2br}</div>
                    {if $v.reply_to}<div class="date replyToInfo">Ответ пользователю <a target="_blank" href="{$urlBase}user/profile/{$v.replyUserID}">{$v.replyNick}</a> на <a href="{$urlBase}{$routePath}/#comment_{$v.replyCommentID}">комментарий</a></div>{/if}
                </div>
            </div> 
        </div>
    </div>
</li>
{foreachelse}
<p class="alert info">Статью пока никто не комментировал. Ваш комментарий может стать первым.</p>
{/foreach}