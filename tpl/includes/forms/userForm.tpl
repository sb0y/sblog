{nocache}
{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<div id="userRegistration">
	<div class="field"><label>E-mail</label><span class="red">*</span><br /><input type="text"{if isset ($errors.email)} class="error"{/if} name="email" value="{fromPost var='email' arr=$fill}" />{if isset ($errors.email)}<div class="errorExplain">{$errors.email.txt}</div>{/if}</div>
	<div class="field"><label>Имя</label><span class="red">*</span><br /><input type="text"{if isset ($errors.nick)} class="error"{/if} name="nick" value="{fromPost var='nick' arr=$fill}" />{if isset ($errors.nick)}<div class="errorExplain">{$errors.nick.txt}</div>{/if}</div>
	<div class="field"><label>Пароль</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password1)} class="error"{/if} name="password1" value="" />{if isset ($errors.password1)}<div class="errorExplain">{$errors.password1.txt}</div>{/if}</div>
	<div class="field"><label>Пароль ещё раз</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password2)} class="error"{/if} name="password2" value="" />{if isset ($errors.password2)}<div class="errorExplain">{$errors.password2.txt}</div>{/if}</div>
	<div class="field"><label>Фото</label><br /><input type="file"{if isset ($errors.avatar)} class="error"{/if} name="avatar" />{if isset ($errors.avatar)}<div class="errorExplain">{$errors.avatar.txt}</div>{/if}</div>
	<div class="req-wrap">
		<div class="field required"><span class="red">*</span>&nbsp;&#151; обязательное поле</div>
	</div>
		
	<div class="field submit">
		<button type="submit">{$sendText}</button>
	</div>
</div>
{/nocache}
