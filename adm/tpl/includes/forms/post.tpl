{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<div class="info-block">
	{*<h3>TEST</h3><br />
	<ul id="messageBox">
		<li>test</li>
	</ul>*}
</div>
<div class="form_textfield">
	<form id="form" class="form" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="poster">Постер</label>
			{if isset ($fill.poster) && $fill.poster}
			<img id="posterImg" src="/content/photo/200x200/{$fill.poster}" width="100">
			{else}
			<span id="posterMsg">Для данной статьи постер не выбран.</span>
			{/if}
			<input id="poster" value="" name="poster" type="hidden" class="file normal">
		</div>

		<div class="form-group">
			<label for="title">Заголовок поста</label>
			<input class="form-control" id="title" name="title" type="text" value="{fromPost var='title' arr=$fill}">
			<input id="key" name="key" type="hidden" value="{fromPost var='key' arr=$fill}">
		</div>

		<div class="form-group">
			<label for="slug">URL</label>
			<input class="form-control" id="slug" name="slug" type="text" value="{fromPost var='slug' arr=$fill}">
		</div>

		{if $cats}
		<div class="form-group">
			<label for="catSelect">Категория 
				<select class="form-control" name="categories[]" id="catSelect" multiple="multiple">
				{foreach $cats as $key => $value}
					<option value="{$value.categoryID}"{if isset ($value.catSel) && $value.catSel>0} selected="selected"{/if}>{$value.catName}</option>
				{/foreach}
				</select>
			</label>
			<a id="showCategoryEntry" href="javascript:;"><span></span></a>
			<span id="newCatInput" style="display: none;">
				<input type="text" id="catName" name="catName" placeholder="Название категории">
				<input type="text" id="catSlug" placeholder="slug категории" name="catSlug">
				<button type="button" class="btn btn-default" title="добавить" id="addCat">добавить</button>
			</span>
		</div>
		{/if}

		<div class="form-group">
			<label for="dt">Дата</label>
			<input class="form-control dt-pic" id="dt" name="dt" type="text" value="{if !isset ($fill.dt)}{$smarty.now|date_format:"%d-%m-%Y"}{else}{fromPost var='dt' arr=$fill}{/if}" />
		</div>

		<div class="form-group">
			<label for="short">Анонс</label>
			<textarea rows="5" class="form-control" id="post-admin-body-short" name="short">{fromPost var='short' arr=$fill}</textarea>
			{if $annotationRestriction}
			<div id="symbolsCount">
				<div class="symbolsCountWrap" id="symbolsLeftText" style="padding:10px;">Осталось символов: <span id="symbolCountInt">140</span></div>
			</div>
			{/if}
		</div>

		<div class="form-group">
			<label for="body">Текст</label>
			<textarea rows="20" class="form-control" id="post-admin-body" name="body">{fromPost var='body' arr=$fill}</textarea>
		</div>

		<div class="checkbox">
			<label for="showOnSite"><input id="showOnSite" class="" type="checkbox" name="showOnSite" value="Y"{if isset ( $fill.showOnSite ) && $fill.showOnSite=='Y'} checked="checked"{/if}> Показывать на сайте</label>
		</div>

   		<div class="form-group">
   			<button class="btn btn-primary" type="submit" name="savePost">Отправить</button>
			<button id="loadDraftList" class="btn btn-default" type="button" title="Черновики" name="saveDraft">Черновики</button>
		</div>
</form>
</div>