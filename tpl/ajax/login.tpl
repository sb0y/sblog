<form id="loginForm" method="post" action="{$urlBase}user/login?to={$routePath|urlencode nocache}">
	<div class="field">
		<label>Email пользователя:</label>
		<div class="input"><input type="text" name="email" value="" id="login" /></div>
	</div>

	<div class="field">
		<label>Пароль:</label>
		<div class="input"><input type="password" name="password" value="" id="pass" /></div>
	</div>
	<div class="field">
		<a href="{$urlBase}user/passwordRestore" id="forgot">Забыли пароль?</a>
	</div>
	<div class="submit">
		<button type="submit" class="btn dark wide">Войти</button>
		<label id="remember"><input name="rememberMe" type="checkbox" value="yes" /> Запомнить меня</label>
	</div>
	<div class="field">
		<ul class="socials">
            <li class="vkontakte"><a href="{$urlBase}user/login/through/vkontakte?to={$routePath|urlencode nocache}"> Войти через ВКонтакте</a></li>
            <li class="twitter"><a href="{$urlBase}user/login/through/twitter?to={$routePath|urlencode nocache}"> Войти через Twitter</a></li>
            <li class="facebook"><a href="{$urlBase}user/login/through/facebook?to={$routePath|urlencode nocache}"> Войти через Facebook</a></li>
            <li class="google"><a href="{$urlBase}user/login/through/google?to={$routePath|urlencode nocache}"> Войти через Google Plus</a></li>
        </ul>
	</div>
</form>
