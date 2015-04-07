{extends file="base.tpl"}
{block name=title}Статистика сервера{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resource/js/sort.js"></script>
<link rel="stylesheet" href="{$urlBase}resource/css/tables.css" type="text/css" media="all" />
{/block}
{block name=body}
<div class="server-status post">
	<div class="page-header">
		<h1>Статистика сервера</h1>
	</div>
	<p>Сайт bagrintsev.me располагается на собственном физическом сервере.<br />
	В качестве серверной <abbr title="Операционная Система">ОС</abbr> используется <abbr title="Linux From Scratch, Линукс с нуля, конструктор">LFS</abbr> <a target="_blank" href="http://www.gentoo.org">Gentoo Linux</a>, собираемая из stage 3.</p>

	<h3>Общая характеристика сервера</h3>

	<div class="table-responsive">
		<table class="infoTable table table-bordered table-striped">
			<tbody>
				<tr>
					<td align="center">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/intel-2-icon.png" alt="cpu model" title="cpu model" />
					</td>
					<td>
						<strong>ЦП</strong>
					</td>
					<td>
						<span>{$cpu}</span>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/ram.png" alt="ram total" title="ram total" />
					</td>
					<td>
						<strong>Вместимость ОЗУ</strong>
					</td>
					<td>
						<span>{$totalMemory} GB</span>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/kernel.png" alt="kernel" title="kernel" />
					</td>
					<td>
						<strong>Ядро</strong>
					</td>
					<td>
						<span>{$kernelVersion}</span>
					</td>
				</tr>
				{*<tr>
					<td align="left">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/monitor-icon.png" alt="ram usage" title="ram usage" />
						<strong>Загруженность ОЗУ</strong>
						<span>{$memory}%</span>
					</td>
				</tr>*}
				<tr>
					<td align="center">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/up-alt-icon.png" alt="uptime" title="uptime" />
					</td>
					<td>
						<strong>Uptime</strong>
					</td>
					<td>
						<span>{$uptime}</span>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img class="tableHeadIcon" src="{$urlBase}resource/images/server-stats/loaded-truck.png" alt="load" title="load" />
					</td>
					<td>
						<strong>Load Avearge</strong>
					</td>
					<td>
						<span>{$load}
					</td>
				</tr>

			</tbody>
		</table>
	</div>

	<h3>Рабочие показатели загруженности сервера на текущий час</h3>
	
	<div class="table-responsive">
		<table class="infoTable table table-bordered table-striped">
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
	</div>
	
	<h3>Графики загруженности системы на текущие 24 часа</h3>
	
	<div class="graps">
		<div class="row">
			<div class="col-md-6">
				<a href="http://munin.bagrintsev.me/fabian/fabian/entropy.html" target="_blank">
					<img class="img-responsive" src="http://munin.bagrintsev.me/fabian/fabian/memory-day.png" alt="Memory usage" title="Memory usage" />
				</a>			
			</div>
			<div class="col-md-6">
				<a href="http://munin.bagrintsev.me/fabian/fabian/cpu.html" target="_blank">
					<img class="img-responsive" src="http://munin.bagrintsev.me/fabian/fabian/cpu-day.png" alt="CPU usage" title="CPU usage" />
				</a>
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-md-6">
				<a href="http://munin.bagrintsev.me/fabian/fabian/entropy.html" target="_blank">
					<img class="img-responsive" src="http://munin.bagrintsev.me/fabian/fabian/entropy-day.png" alt="Available entropy" title="Available entropy" />
				</a>
			</div>
			<div class="col-md-6">
				<a href="http://munin.bagrintsev.me/fabian/fabian/load.html" target="_blank">
					<img class="img-responsive" src="http://munin.bagrintsev.me/fabian/fabian/load-day.png" alt="Load average" title="Load average" />
				</a>
			</div>
		</div>
	</div>

	<h3>Версии установленного ПО</h3>
	
	<div class="table-responsive">
		<table class="sortable infoTable table table-bordered table-striped">
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
</div>
{/block}
