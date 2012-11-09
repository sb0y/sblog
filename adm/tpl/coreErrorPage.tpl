<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" dir="ltr" lang="en-US"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->

<head>
	<title>Критическая ошибка ядра</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
</head>

<body>
	<div style="border: 1px solid #000000;border-radius: 4px;box-shadow: 10px 10px 10px #4C4C4C;width:60%;margin:auto;margin-top:20%;background:#FF8383;">
		<div style="padding:10px;">
		<p>В ходе выполнения программы, найдены ошибки, из-за которых пришлось остановиться.</p>
		<ul>
			{nocache}{foreach $errors as $k=>$v}
			<li>{$v}</li>
			{/foreach}{/nocache}
		</ul>
		</div>
	</div>
</body>

</html>
