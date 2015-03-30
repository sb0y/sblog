{block name=title}Регистрация пользователя{/block}
{block name=body}
<div class="userRegistration">
		{if !isset ($successReg) nocache}
			<h1>Регистрация пользователя</h1>
			<br>
			<form class="form-horizontal" role="form" method="post" name="registration" action="{$urlBase}user/registration" enctype="multipart/form-data">

				<div class="form-group{if isset ($errors.email)} has-error{/if}">
	    			<label for="inputEmail" class="col-sm-3 control-label"><span class="text-warning">*</span>&nbsp;E-mail</label>
	    			<div class="col-sm-5">
	      				<input type="email" name="email" value="{fromPost var='email' arr=$fill}" class="form-control" id="inputEmail" placeholder="Email" />
	    			</div>
		    		{if isset ($errors.email)}
	    			<span class="help-block help-danger">
	    				{$errors.email.txt}
	    			</span>
	    			{/if}
	  			</div>

	  			<div class="form-group{if isset ($errors.nick)} has-error{/if}">
	    			<label for="inputNick" class="col-sm-3 control-label">&nbsp;Имя</label>
	    			<div class="col-sm-5">
	      				<input type="text" name="nick" value="{fromPost var='nick' arr=$fill}" class="form-control" id="inputNick" placeholder="Имя" />
	    			</div>
		    		{if isset ($errors.nick)}
	    			<span class="help-block help-danger">
	    				{$errors.nick.txt}
	    			</span>
	    			{/if}
	  			</div>

	  			<div class="form-group {if isset ($errors.password1)} has-error{/if}">
	    			<label for="inputPassword" class="col-sm-3 control-label"><span class="text-warning">*</span>&nbsp;Пароль</label>
	    			<div class="col-sm-5">
	      				<input type="password" name="password1" value="{fromPost var='password1' arr=$fill}" class="form-control" id="inputPassword" placeholder="Пароль" />
	    			</div>
		    		{if isset ($errors.password1)}
	    			<span class="help-block help-danger">
	    				{$errors.password1.txt}
	    			</span>
	    			{/if}
	  			</div>

	  			<div class="form-group{if isset ($errors.password2)} has-error{/if}">
	    			<label for="inputPassword2" class="col-sm-3 control-label"><span class="text-warning">*</span>&nbsp;Пароль ещё раз</label>
	    			<div class="col-sm-5">
	      				<input type="password" name="password2" value="{fromPost var='password2' arr=$fill}" class="form-control" id="inputPassword2" placeholder="Пароль ещё раз" />
	    			</div>
		    		{if isset ($errors.password2)}
	    			<span class="help-block help-danger">
	    				{$errors.password2.txt}
	    			</span>
	    			{/if}
	  			</div>

				<div class="form-group">
					<label for="inputAvatar" class="col-sm-3 control-label">Фото</label>
	    			<div class="col-sm-5">
	      				<input type="file" name="avatar" value="{fromPost var='avatar' arr=$fill}" class="form-control" id="inputAvatar" placeholder="Фото" />
	    			</div>
		    		{if isset ($errors.avatar)}
	    			<span class="help-block help-danger">
	    				{$errors.avatar.txt}
	    			</span>
	    			{/if}
	  			</div>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						<div class="req-wrap">
							<div class="field required"><span class="text-danger">*</span>&nbsp;&#151; обязательное поле</div>
						</div>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-5">
						<button type="submit" class="btn btn-primary">Зарегистрироваться</button>
					</div>
				</div>
			</form>
		{else}
		{include file="ajax/userSuccessRegister.tpl" nocache}
		{/if}
</div>

{/block}
