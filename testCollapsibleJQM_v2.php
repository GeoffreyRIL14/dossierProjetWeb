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

		<div data-role = "collapsible" id = "notif_1">
			<h2> travaux sur la route </h2>
			<p> route barrée par un tractopelle </p>
			<button class="ui-btn ui-btn-inline ui-mini">Editer</button>
			<button class="ui-btn ui-btn-inline ui-mini" onclick="$('#notif_1').hide()">Masquer</button>
		</div>

		<div data-role = "collapsible" id = "notif_2">
			<h2> ragondin mort </h2>
			<p> il y a des morceaux partout... </p>
			<button class="ui-btn ui-btn-inline ui-mini">Editer</button>
			<button class="ui-btn ui-btn-inline ui-mini" onclick="$('#notif_2').hide()">Masquer</button>
		</div>

	</div><!-- /content -->

	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->

</body>