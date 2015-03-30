{block name=title}Авторизация{/block}
{block name=pageScripts}{/block}
{block name=body}
<div class="auth">
		<div class="page-header">
			<h1>Авторизация</h1>
		</div>

 		<form class="form-horizontal" role="form" id="loginForm" method="post">
  			<div class="form-group">
    			<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
    			<div class="col-sm-10">
      				<input name="email" type="email" class="form-control" id="inputEmail3" placeholder="Email" />
    			</div>
  			</div>
  			<div class="form-group">
	    		<label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
	    		<div class="col-sm-10">
	      			<input name="password" type="password" class="form-control" id="inputPassword3" placeholder="Пароль" />
					<p class="help-block"><a href="{$urlBase}user/passwordRestore" id="forgot">Забыли пароль?</a></p>
	    		</div>
  			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label id="remember"><input name="rememberMe" type="checkbox" value="yes"> Запомнить меня</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Войти</button>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<hr>
					<ul class="socials" style="padding:0;margin:0;">
			            <li class="vkontakte"><a href="{$urlBase}user/login/through/vkontakte?to={$routePath|urlencode nocache}"> Войти через ВКонтакте</a></li>
			            <li class="twitter"><a href="{$urlBase}user/login/through/twitter?to={$routePath|urlencode nocache}"> Войти через Twitter</a></li>
			            <li class="facebook"><a href="{$urlBase}user/login/through/facebook?to={$routePath|urlencode nocache}"> Войти через Facebook</a></li>
			            <li class="google"><a href="{$urlBase}user/login/through/google?to={$routePath|urlencode nocache}"> Войти через Google Plus</a></li>
			        </ul>
				</div>
			</div>
		</form>
</div>
{/block}