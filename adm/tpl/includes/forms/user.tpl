{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
<div class="header">
	<div class="if_sticky">
    <a href="{$urlBase}users"><i class="icon close"></i></a>
    <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
    </div>
</div>
<div class="offset_20">
    <div class="row context">
        <div class="col span_10">
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

				
			</fieldset>
		</div>
	</div>
</div>
</form>