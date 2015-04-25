{nocache}<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Screenshot image from the QScreenShotter program">
	<meta name="author" content="andrey@bagrintsev.me">

	<meta property="og:image" content="{$urlBase}content/screenshots/{$userID}/{$img.small.name}">
	<meta property="og:title" content="QScreenShotter screen image">
    <meta property="og:type" content="website">
	<meta property="og:url" content="{$urlBase}{$calledController}/display/{$img.big.name}/{$img.small.name}">
	<meta property="og:site_name" content="{$siteDomain}">
	<meta property="og:description" content="Screenshot image from the QScreenShotter program">
	<link rel="image_src" href="{$urlBase}content/screenshots/{$userID}/{$img.big.name}">

	<title>Screenshot view</title>

	<!-- Bootstrap Core CSS -->
	<link href="{$urlBase}resources/css/bootstrap.min.css" rel="stylesheet" />
	<link href="{$urlBase}resources/css/bootstrap-theme.min.css" rel="stylesheet" />    

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<link rel="shortcut icon" href="{$urlBase}resources/images/favicon.ico" />

	<!-- Put this script tag to the <head> of your page -->
	<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>

	<!-- jQuery -->
	<script type="text/javascript" src="{$urlBase}resources/js/jquery-1.10.2.min.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script type="text/javascript" src="{$urlBase}resources/js/bootstrap.min.js"></script>

	<script type="text/javascript">
		var urlBase = "{$urlBase}";{nocache}
		var publicSession = {literal}{{/literal} {if !empty($smarty.session.user)}userID:{$smarty.session.user.userID},nick:'{$smarty.session.user.nick}'{/if} {literal}}{/literal};{/nocache}
	</script>

	{block name=pageScripts nocache}{/block}
	{literal}
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-3086718-3', '.bagrintsev.me');
		ga('create', 'UA-3086718-3', '.bagrincev.ru');
		ga('send', 'pageview');
	</script>
	{/literal}

	<style>
		html,
		body {
			height: 100%;
		}

		.carousel,
		.item,
		.active
		{
			height: 100%;
			text-align: center;
		}

		.carousel-inner
		{
			height: 100%;
		}

		.carousel-indicators li
		{
			box-shadow: 0 0 30px rgba(0,0,0,3)!important;
		}

		/* Background images are set within the HTML using inline CSS, not here */

		.fill
		{
			background-position: center;
			background-repeat: no-repeat;
		}

		.fill img
		{
			width: auto;
 			max-width: 100%;
		}

		.navbar
		{
			border-radius: 0 0 5px 5px;
			margin:0 0 2px 0;
		}

		.tpl
		{
			display:none;
		}
	</style>
</head>

<body>

		<div class="tpl tpl-share">
			<div class="tpl-header">Share Link</div>
			<div class="tpl-content">

				<form role="form">
					<div class="form-group">
						<label for="link">Link to this page</label>
						<input id="link" onclick="this.select()" class="form-control editable" type="text" value="{$urlBase}{$calledController}/display/[%BIG%]/[%SMALL%]">
					</div>

					<div class="form-group">
						<label for="link">Direct link to image</label>
						<input id="link" onclick="this.select()" class="form-control editable" type="text" value="{$urlBase}content/screenshots/{$userID}/[%BIG%]">
					</div>
				</form>

				{literal}<!-- Put this script tag to the place, where the Share button will be -->
				<script type="text/javascript"><!--
				document.write(VK.Share.button(false,{type: "custom", text: "<img src=\"http://vk.com/images/share_32_eng.png\" width=\"32\" height=\"32\" />", eng: 1}));
				--></script>{/literal}

				<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/" data-layout="icon"></div>

			</div>
		</div>

		<div class="tpl tpl-codes">
			<div class="tpl-header">Embed Codes</div>
			<div class="tpl-content">

				<form role="form">
					<div class="form-group">
						<label for="bbcode">BB code</label>
						<textarea rows="3" id="bbcode" onclick="this.select()" class="form-control editable">
						[img]{$urlBase}content/screenshots/{$userID}/[%SMALL%][/img]
						</textarea>
					</div>

					<div class="form-group">
						<label for="bbcodeThum">BB code with preview</label>
						<textarea rows="3" id="bbcodeThum" onclick="this.select()" class="form-control editable">
						[url={$urlBase}content/screenshots/{$userID}/[%BIG%]][img]{$urlBase}content/screenshots/{$userID}/[%SMALL%][/img][/url]
						</textarea>
					</div>

					<div class="form-group">
						<label for="htmlcode">HTML code</label>
						<textarea rows="3" id="htmlcode" onclick="this.select()" class="form-control editable">
							<img src="{$urlBase}content/screenshots/{$userID}/[%BIG%]" title="Screenshot from QSrcreenShotter" alt="Screenshot">
						</textarea>
					</div>

					<div class="form-group">
						<label for="htmlcode">HTML code with preview</label>
						<textarea rows="3" id="htmlcode" onclick="this.select()" class="form-control editable">
							<a title="Screenshot from QSrcreenShotter" href="{$urlBase}content/screenshots/{$userID}/[%BIG%]"><img src="{$urlBase}content/screenshots/{$userID}/[%SMALL%]" alt="Screenshot"></a>
						</textarea>
					</div>
				</form>
			</div>
		</div>

		<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="exampleModalLabel">New message</h4>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<strong><a title="This is a program that made this awesome screenshot!" class="navbar-brand" target="_blank" href="https://github.com/sb0y/QScreenShotter">QScreenShotter</a></strong>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li>
							<a class="editable" href="{$urlBase}{$calledController}/download/{$userID}/[%BIG%]">Download</a>
						</li>

						<li>
							<a class="editable" target="_blank" href="{$urlBase}content/screenshots/{$userID}/[%BIG%]">Open original</a>
						</li>

						<li>
							<a class="editable" target="_blank" href="{$urlBase}content/screenshots/{$userID}/[%SMALL%]">Open thumbnail</a>
						</li>

						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#" id="share" aria-expanded="false">Share <span class="caret"></span></a>
							<ul class="dropdown-menu" aria-labelledby="share">
								<li><a href="#" data-toggle="modal" data-target="#mainModal" data-tpl="tpl-share">Link</a></li>
								<li><a href="#" data-toggle="modal" data-target="#mainModal" data-tpl="tpl-codes">Embed codes</a></li>
							</ul>
						</li>

						<li>
							<a title="This is a program that made this awesome screenshot!" target="_blank" href="https://github.com/sb0y/QScreenShotter">Download <strong>QScreenShotter</strong></a>
						</li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>
	<!-- Put your page content here! -->
<!-- Full Page Image Background Carousel Header -->
	<header id="myCarousel" class="carousel slide" data-interval="false">

		<!-- Indicators -->
		<ol class="carousel-indicators">
		{foreach $other as $k => $v}
			<li data-target="#myCarousel" data-slide-to="{$v@index}"{if $v@index==0} class="active"{/if}></li>
		{/foreach}
		</ol>

		<!-- Wrapper for Slides -->
		<div class="carousel-inner">
			<div class="item active">
				<!-- Set the first background image using inline CSS below. -->
				<div class="fill">
					<img data-busy="false" data-imgs="{$img.big.name}/{$img.small.name}" src="" data-src="{$img.big.name}">
				</div>
				<div class="carousel-caption">
					<h2>Upload date: {$img.big.dt|date_format:"%H:%m %m.%d.%Y"}</h2>
				</div>
			</div>

			{foreach $other as $k => $v}
			<div class="item">
				<!-- Set the second background image using inline CSS below. -->
				<div class="fill">
					<img data-busy="false" data-imgs="{$v.big.name}/{$v.small.name}" src="" data-src="{$v.big.name}">
				</div>
				<div class="carousel-caption">
					<h2>Upload date: {$v.big.dt|date_format:"%H:%m %m.%d.%Y"}</h2>
				</div>
			</div>
			{/foreach}
		</div>

		<!-- Controls -->
		<a class="left carousel-control" href="#myCarousel" data-slide="prev">
			<span class="icon-prev"></span>
		</a>
		<a class="right carousel-control" href="#myCarousel" data-slide="next">
			<span class="icon-next"></span>
		</a>

	</header>

	<script type="text/javascript">

		function scroll()
		{
			$( ".active .fill img" ).load ( function()
			{
				$( "html body" ).animate (
				{
					scrollTop: $( "#myCarousel" ).offset().top
				}, 1000 );
			});
		}

		function init ( $imgs, forceReload )
		{
			var w = $( window ).width();
			var h = $( window ).height();

			$imgs.each ( function ( i )
			{
				$this = $( this );

				if ( !forceReload && $this.attr ( "src" ) !== "" )
				{
					return true;
				}

				$this.attr ( "src", "{$moduleResources}images/ajax-loader.gif" );
				$this.attr ( "data-busy", "true" );
				$this.attr ( "style", "position:absolute;bottom:"+( $( window ).height() / 2 - 80 )+"px;right:"+( ( $( window ).width() / 2 - 140 ) ) + "px;" );

				var src = $this.data ( "src" );

				$.get ( "{$urlBase}{$calledController}/resize/{$userID}/"+src+"/"+w+"x"+h, 
				function ( data )
				{
					$this.hide();
					$this.attr ( "style", "" );
					$this.attr ( "src", data );
					$this.fadeIn ( "fast" );
					$this.attr ( "data-busy", "false" );
				});
			});
		}

		function load ( forceReload )
		{
			$imgs = $( ".active .fill img" );
			init ( $imgs, forceReload );
		}

		function update ( big, small )
		{
			var $objs = $( ".editable" );
			var TPLVars = { "BIG" : big, "SMALL" : small };

			$objs.each ( function ( index, obj )
			{
				$obj = $( obj );
				var str = "";

				switch ( $obj.prop ( "tagName" ).toLowerCase() )
				{
					case "a":
					case "link":
						str = $obj.attr ( "href" );
					break;

					case "meta":
						str = $obj.attr ( "content" );
					break;

					default:
						str = $obj.val()
					break;
				}

				for ( var i in TPLVars )
				{
					str = str.replace ( "[%" + i + "%]", TPLVars [ i ] );
				}

				str = jQuery.trim ( str );

				switch ( $obj.prop ( "tagName" ).toLowerCase() )
				{
					case "input":
						$obj.val ( str );
					break;

					case "textarea":
						$obj.text ( str );
					break;

					case "link":
					case "a":
						$obj.attr ( "href", str );
					break;

					case "meta":
						$obj.attr ( "content", str );
					break;
				}

			});
		}

		$( "#mainModal" ).on ( "show.bs.modal", function ( event ) 
		{
			var button = $( event.relatedTarget ); // Button that triggered the modal
			var tpl = button.data ( "tpl" ) // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			var modal = $( this );

			var tplHeader = $( "." + tpl + " .tpl-header" ).html();
			var $tpl = $( "." + tpl + " .tpl-content" ).clone();

			modal.find ( ".modal-title" ).html ( tplHeader );
			modal.find ( ".modal-body" ).empty().append ( $tpl );
		});

		$( "#myCarousel" ).on ( "slid.bs.carousel", function ( e ) 
		{
			scroll();

			var $img = $( ".carousel-inner .active .fill img" );
			var imgs = $img.data ( "imgs" ).split ( "/" );

			history.pushState ( null, null, "{$urlBase}{$calledController}/display/{$userID}/" + imgs.join ( "/" ) );

			update ( imgs [ 0 ], imgs [ 1 ] );
			load ( false );
		});

		var resizeTimer;

		$(function() 
		{
			$( window ).resize ( function() 
			{
				if ( $( ".active .fill img" ).data ( "busy" ) == "true" )
				{
					return;
				}

				clearTimeout ( resizeTimer );
				resizeTimer = setTimeout ( function() { load ( true ) }, 100 );
			});
		});

		update ( "{$img.big.name}", "{$img.small.name}" );
		load ( false );
		//scroll();
	</script>

</body>
{literal}
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&appId=338129942948740&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
{/literal}

</html>{/nocache}