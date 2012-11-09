{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
	<fieldset id="writePost">
		<p>
			<label for="body">Текст</label>
			<textarea id="body" name="body">{fromPost var='body' arr=$fill}</textarea>
		</p>

		<p>
			<div align="center">
				<input id="button1" type="submit" value="Сохранить" /> 
				<input id="button2" type="reset" value="Сбросить" />
			</div>
   		</p>
	</fieldset>

</form>