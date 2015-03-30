<form id="loginForm" method="post" action="{$urlBase}user/login?to={nocache}{$routePath|urlencode}{/nocache}" class="form-horizontal">

	<div class="form-group">
		<label for="email" class="col-sm-3 control-label">E-mail</label>
		<div class="col-sm-9">
			<input name="email" type="email" class="form-control" id="email" placeholder="vasya@pupkin.ru">
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-3 control-label">Пароль</label>
		<div class="col-sm-9">
			<input type="password" name="password" class="form-control" id="password" placeholder="Ваш пароль">
			<p class="help-block"><a href="{$urlBase}user/passwordRestore" id="forgot">Забыли пароль?</a></p>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<div class="checkbox">
				<label>
	 				<input name="rememberMe" value="yes" type="checkbox"> Запомнить меня
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<button type="submit" class="btn btn-primary">Отправить</button>
		</div>
	</div>

</form>

<div class="eliteOR">ИЛИ</div>
<hr />

<div class="row">
	<div class="col-lg-10 col-lg-offset-1 col-xs-11">
		<ul class="socials">
			<li class="vkontakte"><a href="{$urlBase}user/login/through/vkontakte?to={nocache}{$routePath|urlencode}{/nocache}"> Войти через ВКонтакте</a></li>
			<li class="twitter"><a href="{$urlBase}user/login/through/twitter?to={nocache}{$routePath|urlencode}{/nocache}"> Войти через Twitter</a></li>
			<li class="facebook"><a href="{$urlBase}user/login/through/facebook?to={nocache}{$routePath|urlencode}{/nocache}"> Войти через Facebook</a></li>
			<li class="google"><a href="{$urlBase}user/login/through/google?to={nocache}{$routePath|urlencode}{/nocache}"> Войти через Google Plus</a></li>
		</ul>
	</div>
</div>