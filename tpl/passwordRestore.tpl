{extends file="base.tpl"}
{block name=title}Восстановление пароля{/block}
{block name=body}
<div class="passwordRestore">
		<h1>Забыли пароль?</h1>
		{if !isset ($showPassDialog) && !isset($code) nocache}
		<p>Введите E-mail, указанный при регистрации на сайте.</p>
		<form role="form" id="passwordRequestForm" method="GET" name="passwordRequestForm">
			<div class="form-group{if isset ($errors.email)} has-error{/if}">
	    		<label for="inputEmail" class="control-label"><span class="text-danger">*</span>&nbsp;E-mail</label>
	      		<input type="email" class="form-control" id="inputEmail" placeholder="Введите E-mail" name="email" value="{fromPost var='email' arr=$fill}" />
	    		{if isset ($errors.email)}
	    			<span class="help-block help-danger">
	    				{$errors.email.txt}
	    			</span>
	    		{/if}
	  		</div>
			<div class="form-group">
			    <div class="req-wrap">
					<div class="field required"><span class="text-danger">*</span>&nbsp;&#151; обязательное поле</div>
				</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-default">Напомнить</button>
			</div>
		</form>
		{elseif isset ( $code )}
		{if !isset ($errors.form)}
		<p>Введите Ваш новый пароль</p>
		<form role="form" id="newPasswordSetForm" method="POST" name="newPasswordSetForm">
			<div class="form-group{if isset ($errors.password1)} has-error{/if}">
				<label class="control-label" for="password1"><span class="text-danger">*</span>&nbsp;Пароль</label>
				<input type="password" class="form-control" placeholder="Новый пароль" name="password1" value="" />
				{if isset ($errors.password1)}
					<span class="help-block help-danger">
	    				{$errors.password1.txt}
	    			</span>
	    		{/if}
			</div>
			<div class="form-group{if isset ($errors.password2)} has-error{/if}">
				<label class="control-label" for="password2"><span class="text-danger">*</span>&nbsp;Пароль ещё раз</label>
				<input type="password" name="password2" class="form-control" placeholder="Новый пароль ещё раз" value="" />
				{if isset ($errors.password2)}
					<span class="help-block help-danger">
	    				{$errors.password2.txt}
	    			</span>
	    		{/if}
			</div>
				
			<div class="form-group">
			    <div class="req-wrap">
					<div class="field required"><span class="text-danger">*</span>&nbsp;&#151; обязательное поле</div>
				</div>
			</div>
					
			<div class="form-group">
				<button type="submit" class="btn btn-default">Сохранить</button>
			</div>
		</form>
		{else}
		<br />
		<div class="panel panel-danger">
 			<div class="panel-heading">
				<h3 class="panel-title">Ошибка!</h3>
			</div>
			<div class="panel-body">
				Ваш запрос не найден в системе или был просрочен.
			</div>
		</div>
		{/if}
		{else}
		<p>На электронный почтовый адрес <strong>{$emailForSend}</strong> выслано сообщение.</p>
		<p>Пожайлуста, проверьте Вашу почту.</p>
		{/if}
</div>
{/block}
