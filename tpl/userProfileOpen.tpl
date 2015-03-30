{block name=title}{if $user}Профиль пользователя {$user.nick}{else}Несуществующий профиль{/if}{/block}
{block name=pageScripts}
{*<link rel="stylesheet" href="{$urlBase}resources/image-gallery/css/blueimp-gallery.min.css" />
<script src="{$urlBase}resources/image-gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="{$urlBase}resources/image-gallery/js/bootstrap-image-gallery.min.js"></script>*}
<script type="text/javascript" src="{$urlBase}resources/js/userUtils.js"></script>
{/block}
{block name=body}
{*<!-- Modal -->
<div class="modal fade" id="mailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Новое личное сообщение</h4>
      </div>
      <div class="modal-body">
      	{include file="ajax/writeMessage.tpl"}
      </div>
      <div class="modal-footer">
      	<button onclick="writeMail.sendForm()" type="button" id="sendButton" class="btn btn-primary">Отправить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
    </div>
  </div>
</div>*}
<div class="userProfileOpen">
	{if $user}
	<div class="page-header">
		<h1>Профиль {$user.nick}</h1>
	</div>
	
	<div class="col-md-4 text-center">
		<p>
		{if $user.avatar}
			<a target="_blank" href="{$urlBase}content/avatars/{$user.avatar}">
				<img src="{$urlBase}{$user|resolveAvatar}" alt="Аватар пользователя {$user.nick}" title="Аватар пользователя {$user.nick}" class="img-responsive img-thumbnail">
			</a>
		{else}
			<img src="{$urlBase}resources/images/no-avatar-small.png" alt="У пользователя {$user.nick} нет аватара..." title="У пользователя {$user.nick} нет аватара..." class="img-responsive img-thumbnail">
		{/if}
		</p>
		<div class="profileButtons">
			<p>
				<button data-id="{$userID}" class="sendMailButton btn btn-primary btn-block">
					<span class="glyphicon glyphicon-envelope"></span>
					<span class="hidden-sm hidden-xs btn-text">
						Отправить сообщение
					</span>
				</button>
			</p>
			{if empty ( $smarty.session.user ) || ( !empty ( $smarty.session.user ) && $smarty.session.user.userID != $userID )}
			<p>
				{include "includes/friendButton.tpl"}
			</p>
			{/if}
		</div>
	</div>
	<div class="col-md-8">
			{if $user.nick}
				<label>Имя</label>
				<div class="well well-sm">{$user.nick}</div>
			{/if}
					
			{if $user.email && $user.showEmail=="Y"}
			<label>E-mail</label>
			<div class="well well-sm">
				{mailto address=$user.email encode="javascript_charcode"}
			</div>
			{/if}
			
			{if $user.skype}
			<label>Skype</label>
			<div class="well well-sm">
				<a href="skype:{$user.skype}?call">{$user.skype}</a>
			</div>
			{/if}

			{if $user.facebookURL || $user.source=='facebook'}
			<label>Профиль в Facebook</label>
			<div class="well well-sm">
				<a href="{if $user.source=='facebook'}{$user.profileURL}{else}{$user.facebookURL}{/if}" rel="nofollow" target="_blank">{if $user.source=='facebook'}{$user.profileURL}{else}{$user.facebookURL}{/if}</a>
			</div>
			{/if}

			{if $user.vkURL || $user.source=='vkontakte'}
			<label>Профиль в ВКонтакте</label>
			<div class="well well-sm">
				<a href="{if $user.source=='vkontakte'}{$user.profileURL}{else}{$user.vkURL}{/if}" rel="nofollow" target="_blank">{if $user.source=='vkontakte'}{$user.profileURL}{else}{$user.vkURL}{/if}</a>
			</div>
			{/if}

			{if $user.twitterURL || $user.source=='twitter'}
			<label>Профиль в Twitter</label>
			<div class="well well-sm">
				<a href="{if $user.source=='twitter'}{$user.profileURL}{else}{$user.twitterURL}{/if}" rel="nofollow" target="_blank">{if $user.source=='twitter'}{$user.profileURL}{else}{$user.twitterURL}{/if}</a></div>
			{/if}

			{if $user.gplusURL || $user.source=='google'}
			<label>Профиль в Google Plus</label>
			<div class="well well-sm">
				<a href="{if $user.source=='google'}{$user.profileURL}{else}{$user.gplusURL}{/if}" rel="nofollow" target="_blank">{if $user.source=='google'}{$user.profileURL}{else}{$user.gplusURL}{/if}</a>
			</div>
			{/if}
	</div>
	{else}
	<p>Запрошенный Вами пользователь не найден.</p>
	{/if}
</div>
{/block}
