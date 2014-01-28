{block name=title}Личное сообщение от {$mail.nick}{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Новое личное сообщение от пользователя {$mail.nick}</span>
</div>
{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/newMessage.js"></script>
{/block}
{block name=body nocache}
	<div class="row messageBlock">
		<div class="wrap">
			<div class="senderAvatar">
				<a href="{$urlBase}user/profile/{$mail.senderID}">
					<img src="{$urlBase}{$mail|resolveAvatar}" />
					<div class="nick">
					{$mail.nick}
					</div>
				</a>
			</div>
			<div class="mailSubject">{$mail.subject}</div>
			<div class="mailStrike"></div>
			<div class="mailInfo">
				{$mail.mdt|date_format:"%H:%M %m-%d-%Y"} {if $mail.senderID == $smarty.session.user.userID}отправлено пользователю{else}от пользователя{/if} <a href="{$urlBase}user/profile/{$mail.senderID}">{$mail.nick}</a>
			</div>
			<div class="messageText">
				{$mail.body}
			</div>
			<form id="mailWriteForm" name="replyToMessage" method="POST">
				<textarea name="replyToMessage" class="replyToTextForm"></textarea>
				<div class="mailPanel">
					<button name="sendMail" type="submit" value="sendMail" class="button">Отправить</button>
				</div>
				<input type="hidden" name="messageID" value="{$mail.messageID}" />
			</form>
			<div id="showHistoryButton" class="showHistory" data-senderID="{$mail.senderID}" data-receiverID="{$mail.receiverID}">
				Показать историю переписки с пользователем <strong>{$mail.nick}</strong>
			</div>
			<div style="display:none;" id="messageHolder" class="messages">
				<img class="loadPic" src="{$urlBase}resources/images/ajax-loader.gif" alt="Loading..." title="Loading..." />
			</div>
		</div>
	</div>
{/block}