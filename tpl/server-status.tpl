{extends file="main.tpl"}
{block name=title}Статистика сервера{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resource/js/sort.js"></script>
<link rel="stylesheet" href="{$urlBase}resource/css/tables.css" type="text/css" media="all" />
{/block}
{block name=body}
<div class="posts-list post">
	<p><h1>Статистика сервера</h1></p>
	<p>Сайт bagrintsev.me располагается на собственном физическом сервере.<br />
	В качестве серверной <abbr title="Операционная Система">ОС</abbr> используется <abbr title="Linux From Scratch, Линукс с нуля, конструктор">LFS</abbr> <a target="_blank" href="http://www.gentoo.org">Gentoo Linux</a>, собираемая из stage 3.</p>

	<p><h3>Общая характеристика сервера</h3></p>

	<table class="infoTable">
		<tbody>
			<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/intel-2-icon.png" alt="cpu model" title="cpu model" />
					<strong>ЦП</strong>
					{$cpu}
				</td>
			</tr>
			<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/ram.png" alt="ram total" title="ram total" />
					<strong>Вместимость ОЗУ</strong>
					{$totalMemory} Gb
				</td>
			</tr>
			<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/kernel.png" alt="kernel" title="kernel" />
					<strong>Ядро</strong>
					{$kernelVersion}
				</td>
			</tr>
			{*<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/monitor-icon.png" alt="ram usage" title="ram usage" />
					<strong>Загруженность ОЗУ</strong>
					{$memory}%
				</td>
			</tr>*}
			<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/up-alt-icon.png" alt="uptime" title="uptime" />
					<strong>Uptime</strong>
					{$uptime}
				</td>
			</tr>
			<tr>
				<td align="left">
					<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/loaded-truck.png" alt="load" title="load" />
					<strong>Load Avearge</strong>
					{$load}
				</td>
			</tr>

		</tbody>
	</table>

	<p><h3>Рабочие показатели загруженности сервера на текущий час</h3></p>
	
	<table class="infoTable">
		<thead>
			<th align="center"><abbr title="Центральный Процессор">ЦП</abbr></th>
			<th align="center"><abbr title="Оперативное Запоминающие Устройство, оперативная память, RAM">ОЗУ</abbr></th>
		</thead>
		<tbody>
			<tr align="center">
				<td>
					<table class="tableLoadIndicator">
						<tbody>
							{foreach $cpuScale as $s}
							<tr{if $s.active} class="{if $s.critical}red{else}active{/if}"{/if}>
								<td></td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</td>
				<td>
					<table class="tableLoadIndicator">
						<tbody>
							{foreach $memoryScale as $s}
							<tr{if $s.active} class="{if $s.critical}red{else}active{/if}"{/if}>
								<td></td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</td>
			</tr>
			<tr align="center">
				<td>{$cpuLoad}%</td>
				<td>{$memory}%</td>
			</tr>
		</tbody>
	</table>
	
	<p><h3>Версии установленного ПО</h3></p>
	
	<table class="infoTable sortable">
		<thead>
			<th><div class="tableText">Программа</div></th>
			<th><div class="tableText">Версия</div></th>
			<th><div class="tableText">Тип</div></th>
		</thead>
		<tbody>
			{foreach $programms as $p}
			<tr>
				<td>
					{$p.name}
				</td>
				<td>
					{$p.version}
				</td>
				<td>
					{$p.type}
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
</div>
{/block}
