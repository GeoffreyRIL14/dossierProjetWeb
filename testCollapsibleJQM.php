<!DOCTYPE html>
<html>
<head>
	<title>Page Title</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
</head>

<body>

<!-- Start of first page -->
<div data-role="page" id="foo">

	<div data-role="header">
		<h1>Foo</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<div data-role = "collapsible" data-expanded-icon="carat-u" data-collapsed-icon="carat-d" data-theme="b">
			<h3> <marquee id="ticker" direction="right" loop="1">Attention! des incidents sont signalés dans votre zone!</marquee> </h3>
			<ul data-role = "listview">
				<div data-role = "collapsible">
					<h2> travaux sur la route </h2>
					<p> route barrée par un tractopelle </p>
				</div>
				<div data-role = "collapsible">
					<h2> ragondin mort </h2>
					<p> il y a des morceaux partout... </p>
				</div>
			</ul>
		</div>

		<p>I'm first in the source order so I'm shown as the page.</p>
		<p>View internal page called <a href="#bar">bar</a></p>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->

<!-- Start of second page -->
<div data-role="page" id="bar">

	<div data-role="header">
		<h1>Bar</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<p>I'm the second in the source order so I'm hidden when the page loads. I'm just shown if a link that references my id is beeing clicked.</p>
		<p><a href="#foo">Back to foo</a></p>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->
</body>