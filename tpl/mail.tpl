{block name=title}Личные сообщения пользователя {$smarty.session.user.nick}{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Личные сообщения пользователя {$smarty.session.user.nick}</span>
</div>
{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/mailList.js"></script>
{/block}
{block name=body}
	<div class="entries mailList">

	<a class="button edit icon" href="{$urlBase}user/mail/write">Новое сообщение</a>

	{if $smarty.session.user.mail.box=="inbox"}<div class="mailPanel">
	<div id="mailPanel" class="pull_right hiddenMailPanel"><a id="removeButton" class="button remove icon">Удалить</a>&nbsp;<a id="markAsRead" class="button" href="javascript:;">Пометить как прочитанные</a>&nbsp;<a id="markAsUnread" href="javascript:;" class="button">Пометить как непрочитанные</a></div></div>{/if}

	<div id="mailHood" class="mailHood">
		{*if $emails}<div class="mailTotal">
		{if $smarty.session.user.mail.box=="inbox"}
		Вы получили {$totalMessagesCount} сообщени{if $totalMessagesCount == 1}е{else if $totalMessagesCount>4}й{else if $totalMessagesCount>1}я{else}й{/if}
		{else if $smarty.session.user.mail.box=="outbox"}
		Вы отправили {$totalMessagesCount} сообщени{if $totalMessagesCount == 1}е{else if $totalMessagesCount>4}й{else if $totalMessagesCount>1}я{else}й{/if}
		{/if}</div>{/if*}

		<div class="mailBoxes">
			<ul>
				<li{if $smarty.session.user.mail.box=="inbox"} class="active"{/if} onclick="location.href='{$urlBase}user/mail/inbox'"><a href="{$urlBase}user/mail/inbox">Входящие сообщения</a></li>
				<li{if $smarty.session.user.mail.box=="outbox"} class="active"{/if} onclick="location.href='{$urlBase}user/mail/outbox'"><a href="{$urlBase}user/mail/outbox">Исходящие сообщения</a></li>
			</ul>
		</div>
	</div>

	<form method="POST" name="deleteMessages" id="listForm">
	<table id="mailTable" class="tbl">
		<tbody>
     {foreach $emails as $key => $item}
     	<tr class="messageRow{if $item.isRead=='N'} unreadMail{/if}" data-id="{$item.messageID}">
     		<td class="messageSelect">
     			<input type="checkbox" class="checkBox" name="selectMessages[]" value="{$item.messageID}" />
     		</td>
	     	<td class="messageImg">
	     		<img src="{$urlBase}{$item|resolveAvatar}" alt="Аватар пользователя {$item.nick}" title="Аватар пользователя {$item.nick}" />
	     	</td>
	     	<td class="messageInfo">
	     		<div class="nick"><a href="{$urlBase}user/profile/{if $smarty.session.user.mail.box=="inbox"}{$item.senderID}{else}{$item.receiverID}{/if}">{$item.nick}</a></div>
	     		<div class="dt">{$item.dt|date_format:"%H:%M %e-%m-%y"}</div>
	     	</td>
	     	<td class="messageBody">
	     		<div class="subject">
	     			<a href="{$urlBase}user/mail/message/{$item.messageID}">{$item.subject}</a>
	     		</div>
	     		<div class="body">
	     			{$item.body|truncate:200}
	     		</div>
	     	</td>
	     	<td class="del">
	     		<a class="deleteMail" href="javascript:;">Удалить</a>
	     	</td>
		</tr>
	{foreachelse}
	<p>
		{if $smarty.session.user.mail.box=="inbox"}
		Вам ещё никто не писал =(
		{else}
		Вы ещё никому не писали =(
		{/if}
	</p>
	{/foreach}
	</tbody></table></form></div>
	{include file="includes/pagination.tpl"}
{/block}