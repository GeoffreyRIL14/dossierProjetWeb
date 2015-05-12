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
<div data-role="page" id="pageNonConnecte">

	<div data-role="header">
		<h1>SafeRoad</h1>
	</div><!-- /header -->

	<div role="main" class="ui-content">

	<form>
		<div>
			<input type="text" id="email" value="email">
			<input type="password" id="password" value="mot de passe">
			<a href="#pageConnecte" class="ui-btn ui-mini">Connection</a>
			<a href="#" class="ui-btn ui-mini">Inscription</a>
		</div>
	</form>

	</div><!-- /content -->

	<div data-role="footer">
		
	</div><!-- /footer -->
</div><!-- /page -->

<!-- Start of main page -->
<div data-role="page" id="pageConnecte">

  <div data-role="panel" id="param"> 
    <h2>Modifier les paramètres utilisateurs</h2>
    <p>blablabla..</p>
  </div>

  <div data-role="panel" id="signaler" data-position="right"> 
    <h2>Signaler un incident</h2>
    <select name="type_incident" id="type_incident">
    	<option value="accident">accident</option>
    	<option value="radar mobile">radar mobile</option>
    </select> 
    <textarea name="description" id="description"></textarea>   
  </div>

	<div data-role="header">
		<h3>SafeRoad</h3>
	</div><!-- /header -->

	<div role="main" class="ui-content">
		<div display>

		</div>

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

	<div data-role="footer" data-position="fixed">
		<div data-role="navbar">
			<ul>
		    	<li><a href="#param" data-role="button" data-icon="user">Paramètres</a></li>
		    	<li><a href="#signaler" data-role="button" data-icon="location">Signaler incident</a></li>
	    	</ul>
    	</div>
	</div><!-- /footer -->
</div><!-- /page -->

</body>