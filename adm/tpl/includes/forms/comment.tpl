{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
<div class="header">
	<div class="if_sticky">
    <a href="{$urlBase}news/postsWithComments"><i class="icon close"></i></a>
    <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
    </div>
</div>
<div class="offset_20">
    <div class="row context">
        <div class="col span_10">
			<fieldset id="writePost">
				<p>
					<label for="body">Текст</label>
					<textarea id="body" name="body">{fromPost var='body' arr=$fill}</textarea>
				</p>
			</fieldset>
		</div>
	</div>	
</div>	
</form>