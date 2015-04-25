{extends file="base.tpl"}
{block name=title nocache}Access token{/block}
{block name=body}
	<div class="token">
		<div class="page-header">
			<h1>Access token</h1>
		</div>
		<form role="form">
			<div class="form-group">
				<label for="token">Your access token</label>
				<input onclick="this.select()" class="form-control" type="text" id="token" value="{$smarty.session.user.APIs.screenshot.token|default:'' nocache}">
			</div>
		</form>
		<p>
			Copy in your clipboard this token and past it in the QScreenShotter settings.
		</p>
	</div>
{/block}