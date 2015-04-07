{extends file="base.tpl"}
{block name=title}Страница авторизации{/block}
{block name=body}
<div class="posts-list post">
{if isset ($authRes) && $authRes}
<div class="successRegistration">
	<div class="message">
		<p><b>Привет, <span class="strong">{if $smarty.session.user.nick}{$smarty.session.user.nick}{else}{$smarty.session.user.login}{/if}</span><br />Добро пожаловать</b>. Снова.</p>
	</div>
</div>
{else}
<div class="error-block">
	<div class="message">
		<p>Логин или пароль не подходят. Забыли пароль?</p>
	</div>
</div>
{/if}
</div>
{/block}
