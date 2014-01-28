{nocache}<div class="draftLayer">
	<p>Черновики пользователя <b>{$smarty.session.user.nick}</b></p>
	<p>Черновики хранятся 7 дней.</p>
	<br />
	<p>
		<div class="wrap">
			<span onclick="drafts.activateTab()" id="forArticleThis" class="tab active" style="margin-right:6px;"><a href="javascript:;">Для этой статьи</a></span><span onclick="drafts.activateTab()" id="allUsersArticles" class="tab"><a href="javascript:;">Все черновики</a></span>

			<div id="layerArticle" class="content atricle">
			{foreach $lists.article as $k=>$v}
				<p><a onclick="drafts.loadDraft ( {$v.data|unserialize|@json_encode|escape:'html'} )" href="javascript:;">{if $v.draft_nick}<b>{$v.draft_nick}</b>{else}#<b>{$v.draftID}</b>{/if} ({$v.dt|date_format:"%H:%M:%S %d-%m-%Y"})</a>&nbsp;<a href="javascript:;" data-id="{$v.draftID}" class="deleteDraftButton" onclick="drafts.deleteDraft ( this )">X</a></p>
			{foreachelse}
			<p>Черновиков ещё нет.</p>
			{/foreach}
			<br />
			<div class="saveDraft" onclick="drafts.saveAndHide()">Сохранить эту статью в черновик</div>
			{if !empty ($lists.article)}
			<div class="deleteAll" onclick="drafts.deleteArticleDrafts()">Удалить все для этой статьи</div>
			{/if}
			</div>
			<div id="layerAll" class="content all">
			{foreach $lists.all as $k=>$v}
				<p><a onclick="drafts.loadDraft ( {$v.data|unserialize|@json_encode|escape:'html'} )" href="javascript:;">{if $v.draft_nick}<b>{$v.draft_nick}</b> {/if}<b>{$v.title}</b>&nbsp;({$v.dt|date_format:"%H:%M:%S %d-%m-%Y"})</a>&nbsp;<a href="javascript:;" data-id="{$v.draftID}" class="deleteDraftButton" onclick="drafts.deleteDraft ( this )">X</a></p>
			{foreachelse}
			<p>Черновиков ещё нет.</p>
			{/foreach}
			<br />
			<div class="saveDraft" onclick="drafts.saveAndHide()">Сохранить эту статью в черновик</div>
			{if !empty ($lists.all)}
			<div class="deleteAll" onclick="drafts.deleteAll()">Удалить все</div>
			{/if}
			</div>
		</div>
	</p>
</div>{/nocache}