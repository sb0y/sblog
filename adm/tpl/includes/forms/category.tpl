{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post"{*enctype="multipart/form-data"*}>
<div class="header">
	<div class="if_sticky">
    <a href="{$urlBase}news/categories"><i class="icon close"></i></a>
    <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
    </div>
</div>
<div class="offset_20">
    <div class="row context">
        <div class="col span_10">
			<fieldset id="writeCategory">
				<p>
					<label for="catName">Название категории</label>
					<input id="catName" name="catName" value="{fromPost var='catName' arr=$fill}" />
				</p>

				<p>
					<label for="catSlug">URL категории</label>
					<input id="catSlug" name="catSlug" value="{fromPost var='catSlug' arr=$fill}" />
				</p>
			</fieldset>
		</div>
	</div>
</div>	
</form>