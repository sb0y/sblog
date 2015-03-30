{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
	<div class="form-group">
			<label for="nick">Ник</label>
			<input class="form-control" id="nick" name="nick" value="{fromPost var='nick' arr=$fill}" />
		</div>
		<div class="form-group">
			<label class="control-label" for="email">E-mail</label>
			<input type="email" class="form-control" id="email" name="email" value="{fromPost var='email' arr=$fill}" />
		</div>
		<div class="form-group">
			<label class="control-label" for="source">Источник</label>
			<input class="form-control" id="source" name="source" value="{fromPost var='source' arr=$fill}" />
		</div>
		<div class="form-group">
			<label class="control-label" for="role">Роль</label>
			<input placeholder="Например: admin" class="form-control" id="role" name="role" value="{fromPost var='role' arr=$fill}" />
		</div>
		<div class="form-group">
			<label class="control-label" for="password">Пароль</label>
			<input placeholder="Новый пароль" class="form-control" id="password" type="password" name="password" value="" />
		</div>
		<div class="form-group">
			<button class="btn btn-primary" type="submit">Сохранить</button>
		</div>
</form>