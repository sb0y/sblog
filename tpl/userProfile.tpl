{extends file="base.tpl"}
{block name=title}Панель управления профилем{/block}
{block name=body}
<div class="userProfile">
	<div class="page-header">
		<h1>Панель управления пользователя <strong>{$fill.nick}</strong></h1>
	</div>

	<div class="col-md-3 text-center">
		<h4 class="avatar">Фото</h4>
		{if $fill.avatar}
		<a target="_blank" href="{$urlBase}content/avatars/{$fill.avatar}">
			<img src="{$urlBase}{$fill|resolveAvatar}" alt="Это Вы :)" title="Это Вы :)" class="img-responsive profileAvatar" />
		</a>
		<div class="panelUnderPicture">
			{if $smarty.session.user.source=='direct'}
			<a id="deleteUserAvatar" onclick="return confirm ('Вы уверены?')" href="{$urlBase}user/controlpanel?delUserAvatar=true">Удалить</a>
			{/if}
		</div>
		{else}
		<img src="{$urlBase}resources/images/no-avatar-small.png" alt="У вас нет аватара..." title="У вас нет аватара..." class="img-responsive profileAvatar">
		{/if}
	</div>

	<br />

	<div class="col-md-7">
		
		<div class="alert alert-info">

		<form role="form" id="updateProfileForm" method="post" name="updateProfile" enctype="multipart/form-data">

				<div class="form-group{if isset ($errors.avatar)} has-error{/if}">
		    		<label for="inputAvatar" class="control-label">Фото</label>
		      		<input type="file" name="avatar" value="{fromPost var='avatar' arr=$fill}" class="form-control" id="inputAvatar" placeholder="Ваше фото">
			      	{if isset ($errors.avatar)}
			    	<span class="help-block help-danger">
			    		{$errors.avatar.txt}
			    	</span>
			    	{/if}
		  		</div>

		  		<div class="form-group{if isset ($errors.email)} has-error{/if}">
		  			<label for="inputEmail" class="control-label">E-mail&nbsp;<span class="text-danger">*</span></label>
		      		<input type="email" name="email" value="{if !isset($errors.email)}{fromPost var='email' arr=$fill}{/if}" class="form-control" id="inputEmail" placeholder="Email" />
			      	{if isset ($errors.email)}
			    	<span class="help-block help-danger">
			    		{$errors.email.txt}
			    	</span>
			    	{/if}
			    	<span class="help-block">
						<label><input id="inputShowEmail" type="checkbox" value="Y" name="showEmail"{if $fill.showEmail=="Y"} checked{/if}>&nbsp;Показывать e-mail на сайте</label>
					</span> 
		  		</div>

		  		<div class="form-group{if isset ($errors.nick)} has-error{/if}">
		  			<label for="inputNick" class="control-label">Имя&nbsp;<span class="text-warning">*</span></label>
		      		<input type="text" name="nick" value="{if !isset($errors.nick)}{fromPost var='nick' arr=$fill}{/if}" class="form-control" id="inputNick" placeholder="Имя" />
			      	{if isset ($errors.nick)}
			    	<span class="help-block help-danger">
			    		{$errors.nick.txt}
			    	</span>
			    	{/if}
		    	</div>

		  		<div class="form-group{if isset ($errors.skype)} has-error{/if}">
		  			<label for="inputSkype" class="control-label">Skype</label>
		      		<input type="text" name="skype" value="{fromPost var='skype' arr=$fill}" class="form-control" id="inputSkype" placeholder="Skype" />
			      	{if isset ($errors.skype)}
			    	<span class="help-block help-danger">
			    		{$errors.skype.txt}
			    	</span>
			    	{/if}
		  		</div>

		  		<div class="form-group{if isset ($errors.facebookURL)} has-error{/if}">
		  			<label for="inputFacebook" class="control-label">Facebook</label>
		      		<input type="text" name="facebookURL" value="{if $smarty.session.user.source=='facebook'}{$fill.profileURL}{else}{fromPost var='facebookURL' arr=$fill}{/if}"{if $smarty.session.user.source=='facebook'} disabled{/if} class="form-control" id="inputFacebook" placeholder="Ссылка на Facebook" />
		      		{if isset ($errors.facebookURL)}
			    	<span class="help-block help-danger">
			    		{$errors.facebookURL.txt}
			    	</span>
			    	{/if}
		  		</div>

			  	<div class="form-group{if isset ($errors.vkontakteURL)} has-error{/if}">
			  		<label for="inputVk" class="control-label">VKontakte</label>
			    	<input type="text" name="vkURL" value="{if $smarty.session.user.source=='vkontakte'}{$fill.profileURL}{else}{fromPost var='vkURL' arr=$fill}{/if}"{if $smarty.session.user.source=='vkontakte'} disabled{/if} class="form-control" id="inputVk" placeholder="Ссылка на VKontakte" />
			    	{if isset ($errors.vkURL)}
			    	<span class="help-block help-danger">
			    		{$errors.vkURL.txt}
			    	</span>
			    	{/if}
			  	</div>

			  	<div class="form-group{if isset ($errors.twitterURL)} has-error{/if}">
			  		<label for="inputTwitter" class="control-label">Twitter</label>
			   		<input type="text" name="twitterURL" value="{if $smarty.session.user.source=='twitter'}{$fill.profileURL}{else}{fromPost var='twitterURL' arr=$fill}{/if}"{if $smarty.session.user.source=='twitter'} disabled{/if} class="form-control" id="inputTwitter" placeholder="Ссылка на Twitter" />
			   		{if isset ($errors.twitterURL)}
			    	<span class="help-block help-danger">
			    		{$errors.twitterURL.txt}
			    	</span>
			    	{/if}
				</div>

				<div class="form-group{if isset ($errors.gplusURL)} has-error{/if}">
					<label for="inputGplus" class="control-label">Google Plus</label>
			   		<input type="text" name="gplusURL" value="{if $smarty.session.user.source=='google'}{$fill.profileURL}{else}{fromPost var='gplusURL' arr=$fill}{/if}"{if $smarty.session.user.source=='google'} disabled{/if} class="form-control" id="inputGplus" placeholder="Ссылка на Google Plus" />
			      	{if isset ($errors.gplusURL)}
			    	<span class="help-block help-danger">
			    		{$errors.gplusURL.txt}
			    	</span>
			    	{/if}
				</div>

			  	<div class="form-group{if isset ($errors.password1)} has-error{/if}">
			  		<label for="inputPassword1" class="control-label">Пароль&nbsp;<span class="text-warning">*</span></label>
			    	<input autocomplete="off" type="password" name="password1" value=""{if $smarty.session.user.source!='direct'} disabled{/if} class="form-control" id="inputPassword1" placeholder="Ваш пароль" />
				   	{if isset ($errors.password1)}
				    <span class="help-block help-danger">
				    	{$errors.password1.txt}
				    </span>
				    {/if}
				</div>

				<div class="form-group{if isset ($errors.password2)} has-error{/if}">
					<label for="inputPassword2" class="control-label">Пароль ещё раз&nbsp;<span class="text-warning">*</span></label>
				    <input autocomplete="off" type="password" name="password2" value=""{if $smarty.session.user.source!='direct'} disabled{/if} class="form-control" id="inputPassword2" placeholder="Ваш пароль ещё раз" />
				    {if isset ($errors.password2)}
					<span class="help-block help-danger" style="margin: 5px 0 -5px 0;">
						{$errors.password2.txt}
					</span>
					{/if}
				</div>

				<div class="form-group">
									<hr>

					<div class="req-wrap">
						<div class="required"><span class="text-danger">*</span>&nbsp;&#151; обязательное поле</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4">
						<button type="submit" class="btn btn-default">Сохранить</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
{/block}
