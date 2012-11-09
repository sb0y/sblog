<div class="successRegistration">
	<div class="message">
		{nocache}{if $smarty.session.user.avatar_small}
        <div class="img-wrap"><img alt="Ваша аватара" title="Ваша аватара" src="{$urlBase}content/avatars/{$smarty.session.user.avatar_small}"></div>
        {/if}{/nocache}
		<p><b>Добро пожаловать{nocache}, {if isset ($smarty.session.user.nick)}{$smarty.session.user.nick}{/if}{/nocache}!</b></p>
		<p>Регистрация успешно завершена. Вы можете вернуться на <a href="{$urlBase}">главную страницу</a><br>
		или посетить <a href="{$urlBase}user/controlpanel">контрольную панель пользователя</a>.</p>
	</div>
</div>
