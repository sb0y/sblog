{block name=title}Личное сообщение от {$mail.nick}{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/newMessage.js?v=2"></script>
{/block}
{block name=body nocache}
<div class="messageBlock">

	<div class="page-header">
		<h1>Сообщение от <strong>{$mail.nick}</strong></h1>
	</div>

	<div class="col-md-3 text-center">
		<div class="senderAvatar alert-info alert">
			<a href="{$urlBase}user/profile/{$mail.senderID}">
				<p>
					<img class="img-responsive reset img-thumbnail" src="{$urlBase}{$mail|resolveAvatar}" title="Аватар пользователя {$mail.nick}" alt="Аватар пользователя {$mail.nick}">
				</p>
				<p><strong>{$mail.nick}</strong></p>
			</a>
		</div>
	</div>
	<div class="col-md-9">

		<div class="panel panel-default">
			<div class="panel-heading">
			{if $mail.subject}
				<h4>{$mail.subject}</h4>
				<hr>
			{/if}
			<p>
				{$mail.mdt|date_format:"%m-%d-%Y"} в {$mail.mdt|date_format:"%H:%M"}, {if $mail.senderID == $smarty.session.user.userID}отправлено пользователю{else}от пользователя{/if} <strong><a href="{$urlBase}user/profile/{$mail.senderID}">{$mail.nick}</a></strong>
			</p>
			</div>
			<div class="panel-body">
				{$mail.body|url2href|nl2br}
			</div>
		</div>

		<form class="form-horizontal" role="form" id="mailWriteForm" name="replyToMessage" method="POST">
			<textarea class="form-control" rows="10" name="replyToMessage" class="replyToTextForm"></textarea>
			<p class="form-control-static">
				<button name="sendMail" type="submit" value="sendMail" class="btn btn-primary">Отправить</button>
			</p>
			<input type="hidden" name="messageID" value="{$mail.messageID}" />
		</form>
		<hr>
		<button id="showHistoryButton" class="showHistory btn btn-lg btn-default btn-block" data-senderID="{$mail.senderID}" data-receiverID="{$mail.receiverID}">
			<span class="hidden-xs">Показать историю переписки с <strong>{$mail.nick}</strong></span>
			<span class="visible-xs">Показать историю</span>
		</button>
		<br>
		<div style="display:none;" id="messageHolder" class="messages table-responsive">
			<img class="loadPic" src="{$urlBase}resources/images/ajax-loader.gif" alt="Loading..." title="Loading..." />
		</div>
	</div>
</div>
{/block}