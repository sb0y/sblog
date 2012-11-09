{extends file="main.tpl"}
{block name=body}
<div class="posts-list post">
	<p><span class="strong">{nocache}{$text}{/nocache}</span><br />
		Вы будете перенаправлены <a href="{nocache}{$url}{/nocache}" class="strong">сюда</a> через <span class="strong" id="delay">{nocache}{$delay}{/nocache}</span> сек.
	</p>
</div>
	<script type="text/javascript">
	var int = parseInt (document.getElementById ('delay').innerHTML);
	function timer (int)
	{
		document.getElementById ('delay').innerHTML = int;
		--int;
		
		if (int)
			setTimeout('timer('+int+');',1000);
			
		return false;
	}
	
	timer (int);
	</script>
{/block}
