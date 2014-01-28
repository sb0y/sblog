<button data-id="{$userID}" class="button icon {if $user.friendshipID}remove{else}add{/if} friendButton{if $user.friendshipID} active{/if}">
	{if $user.friendshipID}
	{$user.nick} у Вас в друзьях		
	{else}
	Добавить в друзья
	{/if}
</button>