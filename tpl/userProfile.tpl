{block name=title}Панель управления профилем{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Панель управления профилем</span>
</div>
{/block}
{block name=body}
<div class="row">
	
	<div class="col span_8">
		<div class="pull_left">
		{if $fill.avatar!="NULL" && $fill.avatar}
			<a target="_blank" href="{$urlBase}content/avatars/{$fill.avatar}">
				<img src="{$urlBase}content/avatars/{$fill.avatar}" alt="Это Вы :)" title="Это Вы :)" class="userpic" />
			</a>
			<div class="panelUnderPicture">
			{if $smarty.session.user.source=='direct'}
			<a id="deleteUserAvatar" onclick="return confirm ('Вы уверены?')" href="?delUserAvatar=true">Удалить аватару</a>
			{/if}
			</div>
		{else}
			<img src="{$urlBase}resources/images/no-avatar-small.png" alt="У вас нет аватара..." title="У вас нет аватара..." class="userpic" />
		{/if}
		</div>
		<div class="block pull_left margin_0_10">
		<h2>{$fill.nick}</h2>
		<span class="date">Зарегистрированый пользователь</span>
		</div>
	</div>
	<div class="col span_4">
		<form id="updateProfileForm" method="post" name="updateProfile" enctype="multipart/form-data">		
			<div class="field"><input type="file"{if isset ($errors.avatar)} class="error"{/if} name="avatar"{if $smarty.session.user.source!='direct'} disabled{/if} />{if isset ($errors.avatar)}<div class="errorExplain">{$errors.avatar.txt}</div>{/if}</div>
			<div class="inputs">
				
				<div class="field"><label>Имя</label><br /><input type="text"{if isset ($errors.nick)} class="error"{/if} name="nick" value="{fromPost var='nick' arr=$fill}" />{if isset ($errors.nick)}<div class="errorExplain">{$errors.nick.txt}</div>{/if}</div>
				
				<div class="field"><label>E-mail</label><br /><input type="text"{if isset ($errors.email)} class="error"{/if} name="email" value="{fromPost var='email' arr=$fill}" />{if isset ($errors.email)}<div class="errorExplain">{$errors.email.txt}</div>{/if}</div>

				<div class="field"><label><input type="checkbox"{if isset ($errors.showEmail)} class="error"{/if} value="Y" name="showEmail"{if $fill.showEmail=="Y"} checked {/if} /> Показывать e-mail</label>{if isset ($errors.showEmail)}<div class="errorExplain">{$errors.showEmail.txt}</div>{/if}</div>
	
				<div class="field"><label>Skype</label><br /><input type="text"{if isset ($errors.skype)} class="error"{/if} name="skype" value="{fromPost var='skype' arr=$fill}" />{if isset ($errors.skype)}<div class="errorExplain">{$errors.skype.txt}</div>{/if}</div>

				<div class="field"><label>Профиль в Facebook</label><br /><input type="text"{if isset ($errors.facebookURL)} class="error"{/if} name="facebookURL" value="{if $smarty.session.user.source=='facebook'}{$fill.profileURL}{else}{fromPost var='facebookURL' arr=$fill}{/if}"}{if $smarty.session.user.source=='facebook'} disabled{/if} />{if isset ($errors.facebookURL)}<div class="errorExplain">{$errors.facebookURL.txt}</div>{/if}</div>

				<div class="field"><label>Профиль в ВКонтакте</label><br /><input type="text"{if isset ($errors.vkURL)} class="error"{/if} name="vkURL" value="{if $smarty.session.user.source=='vkontakte'}{$fill.profileURL}{else}{fromPost var='vkURL' arr=$fill}{/if}"}{if $smarty.session.user.source=='vkontakte'} disabled{/if} />{if isset ($errors.vkURL)}<div class="errorExplain">{$errors.vkURL.txt}</div>{/if}</div>

				<div class="field"><label>Профиль в Twitter</label><br /><input type="text"{if isset ($errors.twitterURL)} class="error"{/if} name="twitterURL" value="{if $smarty.session.user.source=='twitter'}{$fill.profileURL}{else}{fromPost var='twitterURL' arr=$fill}{/if}"}{if $smarty.session.user.source=='twitter'} disabled{/if} />{if isset ($errors.twitterURL)}<div class="errorExplain">{$errors.twitterURL.txt}</div>{/if}</div>

				<div class="field"><label>Профиль в Google Plus</label><br /><input type="text"{if isset ($errors.gplusURL)} class="error"{/if} name="gplusURL" value="{if $smarty.session.user.source=='google'}{$fill.profileURL}{else}{fromPost var='gplusURL' arr=$fill}{/if}"}{if $smarty.session.user.source=='google'} disabled{/if} />{if isset ($errors.gplusURL)}<div class="errorExplain">{$errors.gplusURL.txt}</div>{/if}</div>

				<div class="field"><label>Пароль</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password1)} class="error"{/if} name="password1" value="" {if $smarty.session.user.source!='direct'} disabled{/if} />{if isset ($errors.password1)}<div class="errorExplain">{$errors.password1.txt}</div>{/if}</div>
		
				<div class="field"><label>Пароль ещё раз</label><span class="red">*</span><br /><input type="password"{if isset ($errors.password2)} class="error"{/if} name="password2" value="" {if $smarty.session.user.source!='direct'} disabled{/if} />{if isset ($errors.password2)}<div class="errorExplain">{$errors.password2.txt}</div>{/if}</div>

				<div class="req-wrap">
					<div class="field required"><span class="red">*</span>&nbsp;&#151; обязательное поле</div>
				</div>
			
				<div class="field submit">
					<button type="submit">Сохранить</button>
				</div>
			</div>
		</form>
	</div>
</div>

{/block}
