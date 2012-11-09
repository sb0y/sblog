{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post"{*enctype="multipart/form-data"*}>
	<fieldset id="writeCategory">
		<p>
			<label for="catName">Название категории</label>
			<input id="catName" name="catName" value="{fromPost var='catName' arr=$fill}" />
		</p>

		<p>
			<label for="catSlug">URL категории</label>
			<input id="catSlug" name="catSlug" value="{fromPost var='catSlug' arr=$fill}" />
		</p>

		<p>
			<div align="center">
				<input id="button1" type="submit" value="Сохранить" /> 
				<input id="button2" type="reset" value="Сбросить" />
			</div>
   		</p>
	</fieldset>
</form>