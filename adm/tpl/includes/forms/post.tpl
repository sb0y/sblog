{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
	<fieldset id="writePost">
		<p>
			<label for="title">Заголовок поста</label>
			<input class="text" id="title" name="title" type="text" value="{fromPost var='title' arr=$fill}" />
		</p>

		<p>
			<label for="url_name">URL</label>
			<input class="text" id="url_name" name="url_name" type="text" value="{fromPost var='url_name' arr=$fill}" />
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
	
	{if !empty ($picFiles)}
	<fieldset id="uploadedPics">
		<legend>Загруженные картинки</legend>
		<div id="pics">
		{foreach $picFiles as $k=>$v}
		<div id="pic-windows">
			<a target="_blank" href="/content/postImages/{$fill.url_name}/{$v.big}">
				<img src="/content/postImages/{$fill.url_name}/{if isset ($v.small)}{$v.small}{else}{$v.big}{/if}" />
			</a><a class="deletePic" href="/adm/blog/writePost?delPic={$k}&draftName={$fill.url_name|urlencode}"><img src="/adm/resources/img/icons/erase.png" /></a>
			<br />
			{if isset ($v.small)}<b>Код для полноразмерной картинки:</b><br />
			<input class="auto-select" name="" value='<img src="/content/postImages/{$fill.url_name}/{$v.big}" />' size="70px" /><br />{/if}
			<b>Код для превью:</b><br />
			<input class="auto-select" name="" value='<a href="/content/postImages/{$fill.url_name}/{$v.big}"><img src="/content/postImages/{$fill.url_name}/{$v.small}" /></a>' size="70px" />
		</div>
		{/foreach}
		</div>
	</fieldset>
	{/if}

	<fieldset id="picsForPost">
		<legend>Загрузить картинку</legend>
		<p>
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
			<input id="button1" type="submit" name="uploadPicture" value="Загрузить картинку" /> 
		</p>
	</fieldset>
</form>
