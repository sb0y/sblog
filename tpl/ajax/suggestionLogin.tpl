<div id="sglg" class="suggestionLogin">
	<div>
		<p>
			Чтобы выполнить это действие, необходимо <a class="strong" href="javascript:;" onclick="closeAndAuth()">авторизоваться</a> в системе.
		</p>
		<p>Вы так же можете <a class="strong" href="{$urlBase}user/registration">зарегистрироваться</a> или войти через одну из этих социальных сетей:</p>
		<ul class="socials margin_10">
			<li class="vkontakte"><a href="{$urlBase}user/login/through/vkontakte?to={$routePath|urlencode nocache}"> Войти через ВКонтакте</a></li>
	        <li class="twitter"><a href="{$urlBase}user/login/through/twitter?to={$routePath|urlencode nocache}"> Войти через Twitter</a></li>
	        <li class="facebook"><a href="{$urlBase}user/login/through/facebook?to={$routePath|urlencode nocache}"> Войти через Facebook</a></li>
	        <li class="google"><a href="{$urlBase}user/login/through/google?to={$routePath|urlencode nocache}"> Войти через Google Plus</a></li>
	    </ul>
	</div>
</div>{literal}
<script type="text/javascript">
function closeAndAuth ()
{
	document.getElementById ( "sglg" ).parentNode.parentNode._closePopup();
	var popup = new Popup;
	popup.showWindow ( "login" );
}
</script>{/literal}