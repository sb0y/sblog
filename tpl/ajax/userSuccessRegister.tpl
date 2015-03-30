<div class="successRegistration">
<div class="row">
	<div class="panel panel-info">
		<div class="panel-heading">
			Добро пожаловать, <strong>{if isset ($smarty.session.user.nick)}{$smarty.session.user.nick}</strong>{/if}!
		</div>
		<div class="panel-body">
			{if $smarty.session.user.avatar}
	        <div class="col-md-4">
	        	<div class="img-wrap"><img class="img-responsive img-thumbnail" alt="Ваша аватара" title="Ваша аватара" src="{$urlBase}{$smarty.session.user|resolveAvatar}"></div>
	        </div>
	    	<div class="col-md-8">{/if}
				<p>Регистрация успешно завершена.</p> 
				<p>Вы можете вернуться на <a href="{$urlBase}">главную страницу</a> или посетить<br /><a href="{$urlBase}user/controlpanel">свою контрольную панель</a>.</p>
				{if $smarty.session.user.avatar}</div>{/if}
		</div>
	</div>
</div>
</div>