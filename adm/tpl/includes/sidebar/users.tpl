<ul>
    <li><span>Пользователи</span></li>
    <li><a href="{$urlBase}users"  class="report {if $args[0] == 'users' && $calledController=="users"}active{/if}">Список всех</a></li>
    <li><a href="{$urlBase}users/add" class="{if $args[0] == 'add' && $calledController=="users"}active{/if} report">Создать нового пользователя</a></li>
</ul>
