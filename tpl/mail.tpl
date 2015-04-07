{extends file="base.tpl"}
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
	<div class="mailList">
			<br>
			<div class="row">
				<div class="col-md-4 col-xs-2 text-left">
					<a class="btn btn-primary" role="button" href="{$urlBase}user/mail/write">
						<span class="glyphicon glyphicon-pencil"></span>&nbsp;<span class="hidden-xs hidden-sm">Новое сообщение</span>
					</a>
				</div> 
				<div id="mailPanel" class="col-md-8 col-xs-10 hiddenMailPanel text-right">
					<button type="button" id="removeButton" class="btn btn-danger">
						<span class="glyphicon glyphicon-remove"></span>&nbsp;<span class="hidden-xs hidden-sm">Удалить</span>
					</button>
					<div class="dropdown" style="display:inline;">
						<button class="btn btn-info dropdown-toggle" type="button" id="dropdownMark" data-toggle="dropdown" aria-expanded="true">
							<span class="hidden-xs hidden-sm">Отметить как</span>&nbsp;<span class="caret"></span>
						</button>
						<ul style="top:30px;" class="dropdown-menu dropdown-menu-left" role="menu" aria-labelledby="dropdownMark">
							<li id="markAsRead" role="presentation">
								<a role="menuitem" tabindex="-1" href="javascript:;">
									<span class="glyphicon glyphicon-eye-open"></span>&nbsp;<span class="">Отметить как прочитанные</span>
								</a>
							</li>
							<li id="markAsUnread" role="presentation">
								<a role="menuitem" tabindex="-1" href="javascript:;">
									<span class="glyphicon glyphicon-eye-close"></span>&nbsp;<span class="">Отметить как непрочитанные</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>
			<br />

			<div class="panel panel-default">

				<div class="panel-heading">
					<div class="mailBoxes">
						<ul class="nav nav-pills" role="tablist">
							<li>
								<button onclick="location.href='{$urlBase}user/mail/inbox'" class="btn btn-primary{if $smarty.session.user.mail.box=="inbox"} active{/if}" href="{$urlBase}user/mail/inbox">
									Входящие <span class="badge">{$cntIn}</span>
								</button>
							</li>
							<li>
								<button onclick="location.href='{$urlBase}user/mail/outbox'" class="btn btn-primary{if $smarty.session.user.mail.box=="outbox"} active{/if}" href="{$urlBase}user/mail/outbox">
									Исходящие <span class="badge">{$cntOut}</span>
								</button>
							</li>
						</ul>
					</div>
				</div>

				<div class="panel-body">

					<form method="POST" name="deleteMessages" id="listForm">
						<div class="table-responsive">
							<table id="mailTable" class="table table-striped">
								<tbody>
						     	{foreach $emails as $key => $item}
						     	<tr class="messageRow{if $item.isRead=='N'} unreadMail{/if}" data-id="{$item.messageID}">
						     		<td class="messageSelect">
						     			<input type="checkbox" class="checkBox" name="selectMessages[]" value="{$item.messageID}" />
						     		</td>
							     	<td class="messageImg">
							     		<img class="img-rounded" src="{$urlBase}{$item|resolveAvatar}" alt="Аватар пользователя {$item.nick}" title="Аватар пользователя {$item.nick}" />
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
							     		<button title="Удалить" class="deleteMail btn btn-danger" type="button">
							     			<span class="glyphicon glyphicon-remove"></span>
							     		</button>
							     	</td>
								</tr>
								{foreachelse}
								<tr>
								<p>
									{if $smarty.session.user.mail.box=="inbox"}
									Вам ещё никто не писал =(
									{else}
									Вы ещё никому не писали =(
									{/if}
								</p>
								{/foreach}
								</tr>
								</tbody>
							</table>
						</div>
					</form>
				</div>

				<div class="panel-footer">
					{include file="includes/pagination.tpl"}
				</div>
			</div>
		</div>
{/block}