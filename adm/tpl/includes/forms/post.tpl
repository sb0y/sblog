{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" class="form" method="post" enctype="multipart/form-data">
	<fieldset id="writePost">
		<p>
			<label for="title">Заголовок поста</label>
			<input class="text" id="title" name="title" type="text" value="{fromPost var='title' arr=$fill}" />
		</p>

		<p>
			<label for="slug">URL</label>
			<input class="text" id="slug" name="slug" type="text" value="{fromPost var='slug' arr=$fill}" />
		</p>

		<p>
			<label for="category">Категория</label>
			<select name="categories[]" id="catSelect" multiple="multiple">
			{foreach $cats as $key => $value}
				<option value="{$value.categoryID}"{if isset ($value.catSel) && $value.catSel>0} selected="selected"{/if}>{$value.catName}</option>
			{/foreach}
			</select>
			<a id="showCatEntry" href="javascript:;">Добавить новую категорию</a>&nbsp;
			<span id="newCatInput"><input type="text" id="catName" name="catName" />&nbsp;<input type="text" id="catSlug" name="catSlug" />&nbsp;<button type="button" title="добавить" id="addCat"></button></span>
		</p>

		<p>
			<label for="dt">Дата</label>
			<input class="text" id="dt" name="dt" type="text" value="{if !isset ($fill.dt)}{$smarty.now|date_format:"%d-%m-%Y"}{else}{{fromPost var='dt' arr=$fill}|date_format:"%d-%m-%Y"}{/if}" />
		</p>

		<p>
			<label for="body">Текст</label>
			<textarea id="body" name="body">{fromPost var='body' arr=$fill}</textarea>
		</p>

		<p class="checkboxes">
			 <label for="showOnSite">Показывать на сайте</label><input id="showOnSite" class="clear" type="checkbox" name="showOnSite" value="Y"{if $fill.showOnSite=='Y'} checked="checked"{/if} />
		<p>

		<p>
			<div align="center">
				<input id="button1" type="submit" name="savePost" value="Сохранить" /> 
				<input id="button2" type="reset" value="Сбросить" />
			</div>
   		</p>
	</fieldset>
	
	<fieldset id="uploadedPics">
		<legend>Загруженные картинки</legend>
		<div id="pics">
		{if !empty ($picFile)}
		{literal}<style>
			#pics{display:block}
		</style>{/literal}
		{foreach $picFiles as $k=>$v}
		<div id="pic-windows">
			<a target="_blank" href="/content/postImages/{$fill.slug}/{$v.big}">
				<img src="/content/postImages/{$fill.slug}/{if isset ($v.small)}{$v.small}{else}{$v.big}{/if}" />
			</a><a id="{$k}" class="deletePic" href="javascript:;"><img src="/adm/resources/img/icons/erase.png" /></a>
			<br />
			{if isset ($v.small)}<b>Код для полноразмерной картинки:</b><br />
			<input class="auto-select" name="" value='<img src="/content/postImages/{$fill.slug}/{$v.big}" />' size="70" /><br />{/if}
			<b>Код для превью:</b><br />
			<input class="auto-select" name="" value='<a href="/content/postImages/{$fill.slug}/{$v.big}"><img src="/content/postImages/{$fill.slug}/{$v.small}" /></a>' size="70" />
		</div>
		{/foreach}
		{/if}
		</div>
	</fieldset>
</form>
<iframe border="0" size="0" style="display:none;" id="picFrame"></iframe>
<form id="picForm" method="post" enctype="multipart/form-data" class="form">
	<fieldset id="picsForPost">
		<legend>Загрузить картинку</legend>
		<p id="inputFileHolder">
			<label for="picUpld">Файл</label>
			<input id="picUpld" value="" name="picUpld" type="file" class="file" />
		</p>
		<p>
			<label for="picHeight">Высота превью</label>
			<input name="picHeight" type="text" value="200" />
		</p>
		<p>
			<label for="picWidth">Ширина превью</label>
			<input name="picWidth" type="text" value="200" />
		</p>
		<p>
			<input id="button1" type="button" name="uploadPicture" value="Загрузить картинку" /> 
		</p>
	</fieldset>
</form>
