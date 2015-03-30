<button data-id="{$userID}" class="btn-block btn-primary btn friendButton{if $user.friendshipID} active{/if}">
	<span class="glyphicon {if $user.friendshipID}glyphicon-minus{else}glyphicon-plus{/if}"></span>
	<span class="hidden-sm hidden-xs btn-text">
		{if $user.friendshipID}
		Удалить из друзей		
		{else}
		Добавить в друзья
		{/if}
	</span>
</button>