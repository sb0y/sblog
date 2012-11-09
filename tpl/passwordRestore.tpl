{extends file="main.tpl"}
{block name=title}Восстановление пароля{/block}
{block name=body}
<div class="posts-list post">
	<p><h2>Забыли пароль?</h2></p>
	{nocache}
	{if !isset ($showPassDialog) && !isset($code)}
	<p>Введите e-mail, указанный при регистрации на сайте.</p>
	<form id="passwordRequestForm" method="post" name="passwordRequestForm">
		{if !isset ($fill)}
		{assign var=fill value=array()}
		{/if}
		<div class="inputs">
		<div class="field"><label>E-mail</label><span class="red">*</span><br /><input type="text"{if isset ($errors.email)} class="error"{/if} name="email" value="{fromPost var='email' arr=$fill}" />{if isset ($errors.email)}<div class="errorExplain">{$errors.email.txt}</div>{/if}
		</div>
		
		<div class="req-wrap">
			<div class="field required"><span class="red">*</span>&nbsp;&#151; обязательное поле</div>
		</div>
			
		<div class="field submit">
			<button type="submit">Отправить</button>
		</div>
		</div>
	</form>
	{elseif isset ($code)}
	{if isset ($passwordReady)}
	<p>Введите Ваш новый пароль.</p>
	<form id="newPasswordSetForm" method="post" name="newPasswordSetForm">
	<div class="inputs">
		<div class="field"><label>Пароль</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password1)} class="error"{/if} name="password1" value="" />{if isset ($errors.password1)}<div class="errorExplain">{$errors.password1.txt}</div>{/if}</div>
		<div class="field"><label>Пароль ещё раз</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password2)} class="error"{/if} name="password2" value="" />{if isset ($errors.password2)}<div class="errorExplain">{$errors.password2.txt}</div>{/if}</div>
		
		<div class="req-wrap">
			<div class="field required"><span class="red">*</span>&nbsp;&#151; обязательное поле</div>
		</div>
			
		<div class="field submit">
			<button type="submit">Отправить</button>
		</div>
	</div>
	</form>
	{else}
	<p>Ваш запрос не найден в системе или был просрочен.</p>
	{/if}
	{else}
	<p>На электронный почтовый адрес <span class="strong">{$emailForSend}</span> выслано сообщение.</p>
	<p>Пожайлуста, проверьте Вашу почту.</p>
	{/if}
	{/nocache}
</div>
{/block}
