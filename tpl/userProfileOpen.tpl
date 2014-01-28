{block name=title}{if $user}Профиль пользователя {$user.nick}{else}Несуществующий профиль{/if}{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>{if $user}Профиль пользователя {$user.nick}{else}Несуществующий профиль{/if}</span>
</div>
{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/userUtils.js"></script>
{/block}
{block name=body}
<div class="row userProfile open">
	{if $user}
	<div class="col span_8">
		<div class="pull_left">
		{if $user.avatar!="NULL" && $user.avatar}
			<a target="_blank" href="{$urlBase}content/avatars/{$user.avatar}">
				<img src="{$urlBase}content/avatars/{$user.avatar}" alt="Аватар пользователя {$user.nick}" title="Аватар пользователя {$user.nick}" class="userpic" />
			</a>
		{else}
			<img src="{$urlBase}resources/images/no-avatar-small.png" alt="У пользователя {$user.nick} нет аватара..." title="У пользователя {$user.nick} нет аватара..." class="userpic" />
		{/if}
		</div>
		<div class="block pull_left margin_0_10">
			<h2>{$user.nick}</h2>
			{if $user.group!="none"}<div class="groupIcon"><img src="{$urlBase}resources/images/group-icons/{$user.group}.gif" title="{$user.group} group" alt="{$user.group} group" /></div>{/if}
			<div class="date">Профиль пользователя</div>
			<div class="socialButtons">
				<div class="btn">
					<button data-id="{$userID}" class="button icon edit sendMailButton">
					Отправить сообщение
					</button>
				</div>
				{if empty ( $smarty.session.user ) || ( !empty ( $smarty.session.user ) && $smarty.session.user.userID != $userID )}
				<div class="btn">
					{include "includes/friendButton.tpl"}
				</div>
				{/if}
			</div>
		</div>
	</div>
	<div class="col span_4">

			<div class="inputs">
				
				{if $user.nick}<div class="field"><label>Имя</label><br /><p>{$user.nick}</p><br />{/if}
				
				{if $user.email && $user.showEmail=="Y"}<div class="field"><label>E-mail</label><br /><p>{mailto address=$user.email encode="javascript_charcode"}</p></div><br />{/if}
		
				{if $user.skype}<div class="field"><label>Skype</label><br /><p><a href="skype:{$user.skype}?call">{$user.skype}</a></p></div><br />{/if}

				{if $user.facebookURL || $user.source=='facebook'}<div class="field"><label>Профиль в Facebook</label><br /><p><a href="{if $user.source=='facebook'}{$user.profileURL}{else}{$user.facebookURL}{/if}" rel="nofollow" target="_blank">ссылка</a></p></div><br />{/if}

				{if $user.vkURL || $user.source=='vkontakte'}<div class="field"><label>Профиль в ВКонтакте</label><br /><p><a href="{if $user.source=='vkontakte'}{$user.profileURL}{else}{$user.vkURL}{/if}" rel="nofollow" target="_blank">ссылка</a></p></div><br />{/if}

				{if $user.twitterURL || $user.source=='twitter'}<div class="field"><label>Профиль в Twitter</label><br /><a href="{if $user.source=='twitter'}{$user.profileURL}{else}{$user.twitterURL}{/if}" rel="nofollow" target="_blank">ссылка</a></p></div><br />{/if}

				{if $user.gplusURL || $user.source=='google'}<div class="field"><label>Профиль в Google Plus</label><br /><a href="{if $user.source=='google'}{$user.profileURL}{else}{$user.gplusURL}{/if}" rel="nofollow" target="_blank">ссылка</a></p></div><br />{/if}
			
			</div>
	</div>
	{else}
		<div class="col span_4">
		<p>Запрошенный Вами пользователь не найден.</p>
		</div>
	{/if}

</div>

{/block}
