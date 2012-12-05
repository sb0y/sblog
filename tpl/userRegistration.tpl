{extends file="main.tpl"}
{block name=title}Регистрация нового пользователя{/block}
{block name=body}
<div class="posts-list post">
	<p><h2 class="page-title">Регистрация нового пользователя</h2></p>
	{if !isset ($successReg)}
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
	{include file="includes/forms/userForm.tpl" sendText="Зарегистрироваться" nocache}
	</form>
	{else}
	{include file="ajax/userSuccessRegister.tpl" nocache}
	{/if}
</div>
{/block}
