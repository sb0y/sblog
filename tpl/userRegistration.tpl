{block name=title}Регистрация нового пользователя{/block}
{block name=body}

	<h2 class="page-title">Регистрация нового пользователя</h2>
	{if !isset ($successReg) nocache}
	{*if !empty($errors)}
		<div class="error-block">
		<ul>
		{foreach $errors as $k=>$v}
		<li>{$v}</li>
		{/foreach}
		</ul>
		</div>
	{/if*}
	<form method="post" name="registration" action="{$urlBase}user/registration" enctype="multipart/form-data">
	{assign sendText "Зарегистрироваться"}
	{include file="includes/forms/userForm.tpl" nocache}
	</form>
	{else}
	{include file="ajax/userSuccessRegister.tpl" nocache}
	{/if}

{/block}
