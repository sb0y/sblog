{extends file="main.tpl"}
{block name=title}Панель управления профилем{/block}
{block name=body}
<div class="posts-list post updateProfile">
	<p><h2>Профиль пользователя</h2></p>
	<form id="updateProfileForm" method="post" name="updateProfile" enctype="multipart/form-data">
		{if $fill.avatar!="NULL" && $fill.avatar}
		<div class="avatar">
			<div class="title">Фото</div>
			<a target="_blank" href="{$urlBase}content/avatars/{$fill.avatar}">
				<img src="{$urlBase}content/avatars/{$fill.avatar_small}" alt="Это Вы :)" title="Это Вы :)" />
			</a>
			<a target="_blank" href="{$urlBase}content/avatars/{$fill.avatar}" id="avatarFullSize">Увеличить</a>
			<br />
			<div class="field"><input type="file"{if isset ($errors.avatar)} class="error"{/if} name="avatar"{if $smarty.session.source!='direct'} disabled{/if} />{if isset ($errors.avatar)}<div class="errorExplain">{$errors.avatar.txt}</div>{/if}</div>
		</div>
		{/if}
		<div class="inputs">
		<div class="field"><label>Имя</label><br /><input type="text"{if isset ($errors.nick)} class="error"{/if} name="nick" value="{fromPost var='nick' arr=$fill}" />{if isset ($errors.nick)}<div class="errorExplain">{$errors.nick.txt}</div>{/if}</div>
		<div class="field"><label>E-mail</label><br /><input type="text"{if isset ($errors.email)} class="error"{/if} name="email" value="{fromPost var='email' arr=$fill}" />{if isset ($errors.email)}<div class="errorExplain">{$errors.email.txt}</div>{/if}</div>
		<div class="field"><label>Пароль</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password1)} class="error"{/if} name="password1" value="" {if $smarty.session.source!='direct'} disabled{/if} />{if isset ($errors.password1)}<div class="errorExplain">{$errors.password1.txt}</div>{/if}</div>
		<div class="field"><label>Пароль ещё раз</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password2)} class="error"{/if} name="password2" value="" {if $smarty.session.source!='direct'} disabled{/if} />{if isset ($errors.password2)}<div class="errorExplain">{$errors.password2.txt}</div>{/if}</div>

		<div class="req-wrap">
			<div class="field required"><span class="red">*</span>&nbsp;&#151; обязательное поле</div>
		</div>
			
		<div class="field submit">
			<button type="submit">Сохранить</button>
		</div>
		</div>
	</form>
</div>
{/block}
