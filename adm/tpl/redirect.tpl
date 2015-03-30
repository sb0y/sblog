{block name=title}Перенаправление на страницу "{$url nocache}"{/block}
{block name=body}
<div class="block">
	<div class="panel panel-info">
		<div class="panel-heading">
			Перенаправление на <strong><a href="{$url nocache}">страницу</a></strong> через <strong id="delay">{$delay nocache}</strong> сек.
		</div>
		<div class="panel-body">
			<p>{$text nocache}</p>
			<p>Если перенаправление не происходит, нажмите <strong><a href="{$url nocache}">сюда</a></strong>.</p>
		</div>
	</div>
</div>
<script type="text/javascript">{literal}
	var int = parseInt ( document.getElementById ('delay').innerHTML );
	function timer ( int )
	{
		document.getElementById ('delay').innerHTML = int;
		--int;
		
		if ( int )
			setTimeout ( 'timer (' + int + ');', 1000 );
			
		return false;
	}
	
	timer ( int );
</script>{/literal}
{/block}
