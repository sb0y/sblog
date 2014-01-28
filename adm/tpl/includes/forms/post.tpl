{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<div class="info-block">
	<h3>TEST</h3><br />
	<ul id="messageBox">
		<li>test</li>
	</ul>
</div>
<div class="form_textfield">
<form id="form" class="form" method="post" enctype="multipart/form-data">
<div class="header">
	<div class="if_sticky">
    <a href="{$urlBase}{if !isset($gbURL)}news/posts{else}{$gbURL}{/if}"><i class="icon close"></i></a>
    <button class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
	<button id="loadDraftList" class="pull_right" type="button" title="Черновики" name="saveDraft"><i class="icon news"></i></button>
    </div>
</div>
<div class="offset_20">
    <div class="row context">
        <div class="col span_10">
			<fieldset id="writePost">
				<p>
					<label for="title">Заголовок поста</label>
					<input class="text" id="title" name="title" type="text" value="{fromPost var='title' arr=$fill}" />
					<input id="key" name="key" type="hidden" value="{fromPost var='key' arr=$fill}" />
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
					<a id="showCategoryEntry" href="javascript:;"><i class="icon add_mini"></i></a>
					<span id="newCatInput" style="display: none;">
						<input type="text" id="catName" name="catName" placeholder="Название категории" />
						<input type="text" id="catSlug" placeholder="slug категории" name="catSlug" />
						<button type="button" class="btn" title="добавить" id="addCat">добавить</button>
					</span>
				</p>

				<p>
					<label for="dt">Дата</label>
					<input class="text dt-pic" id="dt" name="dt" type="text" value="{if !isset ($fill.dt)}{$smarty.now|date_format:"%d-%m-%Y"}{else}{fromPost var='dt' arr=$fill}{/if}" />
				</p>

				<p>
					<label for="poster">Постер</label>
					{if isset ($fill.poster) && $fill.poster}
					<img id="posterImg" src="/content/photo/200x200/{$fill.poster}" width="100" />

					{else}
					<span id="posterMsg">Для данной статьи постер не выбран.</span>
					
					{/if}
					<input id="poster" value="" name="poster" type="hidden" class="file normal" />
				</p>

				<p>
					<label for="short">Анонс новости</label>
					<textarea style="width:100%;height:100px;" id="post-admin-body-short" name="short">{{fromPost var='short' arr=$fill}|replace:"<br />":"\n"|trim}</textarea>
					<div id="symbolsCount">
						<div class="symbolsCountWrap" id="symbolsLeftText" style="padding:10px;">Осталось символов: <span id="symbolCountInt">140</span></div>
					</div>
				</p>

				<p>
					<label for="body">Текст</label>
					<textarea id="post-admin-body" name="body">{fromPost var='body' arr=$fill}</textarea>
				</p>

				<p >
					 <label for="showOnSite">Показывать на сайте</label><input id="showOnSite" class="clear" type="checkbox" name="showOnSite" value="Y"{if isset ( $fill.showOnSite ) && $fill.showOnSite=='Y'} checked="checked"{/if} />
				<p>

			</fieldset>


		</div>
	</div>
</div>
</form>

<div class="offset_20">
   
</div>
</div>