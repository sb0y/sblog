{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
	<fieldset id="writePost">
		<p>
			<label for="nick">Ник</label>
			<input id="nick" name="nick" value="{fromPost var='nick' arr=$fill}" />
		</p>

		<p>
			<label for="email">E-mail</label>
			<input id="email" name="email" value="{fromPost var='email' arr=$fill}" />
		</p>

		<p>
			<label for="source">Источник</label>
			<input id="source" name="source" value="{fromPost var='source' arr=$fill}" />
		</p>

		<p>
			<label for="role">Роль</label>
			<input id="role" name="role" value="{fromPost var='role' arr=$fill}" />
		</p>

		<p>
			<label for="password">Пароль</label>
			<input id="password" type="password" name="password" value="" />
		</p>

		<p>
			<div align="center">
				<input id="button1" name="savePost" type="submit" value="Сохранить" /> 
				<input id="button2" type="reset" value="Сбросить" />
			</div>
   		</p>
	</fieldset>
</form>