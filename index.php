<?php 
require 'modele.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
	<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
setInterval("recupereIncidents()",1000)
</script>
	<script type="text/javascript">
        var map ;
		//requête ajax permettant d'ajouter un nouvel incident
		function ajoutIncident(description, idType)
		{
			$.ajax({
				type: 'GET',
				url: './ajax/ajoutIncident.php',
				data: '&d=' + description + '&t=' + 1,  
				success: function(data, textStatus, jqXHR){
					console.log(data);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		}

		//Requête Ajax permettant de vérifier si l'utilisateur existe
		function verifUser(mdp)
		{
			var login = document.forms['connexion'].login.value;
			$.ajax({
				type: 'GET',
				url: './ajax/verifUser.php',
				data: '&l=' + login + '&m=' + mdp,  
				success: function(data, textStatus, jqXHR){
					if (data != "") 
						alert(data);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		}

		//Requête ajax recupérant tous les incidents
		function recupereIncidents()
		{
			$.ajax({
				type: 'GET',
				url: './ajax/recupereIncidents.php',
				data: {},  
				success: function(data, textStatus, jqXHR){
					alert(data);
					ajoutMarqueur(data);
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
		}


		//Initialisation de la carte
		$( document ).on( "pagecreate", "#pageConnecte", function() {
			var centreCarte = new google.maps.LatLng(48, 5);
			        var myOptions = {
				            zoom: 7,
				            center: centreCarte,
				            mapTypeId: google.maps.MapTypeId.ROADMAP
			        };
			        var map = new google.maps.Map(document.getElementById("map"), myOptions);       
		});

		//Ajoute UN marqueur et affiche la description de l'incident sur le click
		function ajoutMarqueur(pos, desc)
		{
			var optionsMarqueur = {
			position: pos,
			map: map,
		}
		var marqueur = new google.maps.Marker(optionsMarqueur);
		var contenuInfoBulle = ' Tchou Tchou je suis un bus ';
		var infoBulle = new google.maps.InfoWindow({
			content: desc
		})
		google.maps.event.addListener(marqueur, 'click', function() {
			infoBulle.open(maCarte, marqueur);
		});
		}
	</script>

</head>
<body>


	<!-- page connecté -->
	<div data-role="page" id="pageConnecte">
		
		<div data-role="panel" id="signaler" data-position="right"> 
			<h2>Signaler un incident</h2>
			<form name = "incident" >
				<select name="typeIncident" id="typeIncident">
					<optgroup label="Type d'incident">
						<option value="acc">Accident</option>
						<option value="anm">Animal mort</option>
						<option value="rad">Radar</option>
					</optgroup>
				</select>

				<label for="info">description</label>
				<textarea name="desc" id="description" style="height:40%"></textarea>
			</form>
			<a href="#myPopup" data-rel="popup" onclick = "ajoutIncident(document.forms['incident'].desc.value, document.forms['incident'].typeIncident.value);"  class="ui-btn ui-btn-inline ui-corner-all ui-shadow" style="margin: auto; ">Envoyer</a>
			<a href="#pageConnecte" class="ui-btn ui-btn-inline ui-corner-all ui-shadow" style="margin: auto; ">retour</a>
		</div>

		<div data-role="panel" id="param"> 
			<h2>Modifier les paramètres utilisateurs</h2>
			<p>blablabla..</p>
		</div>

		<div data-role="popup" id="myPopup">
		<p>Alerte ajoutée</p>
		</div>

		<div data-role="content" class="ui-content">
			<p>SafeRoad<p>
				<p>Parce que nous qu'on aime bien les routes sures<p>
					<div id="map" style="height:500px; width:100%;" ></div>
					<a href="#pageNonConnecte" class="ui-btn ui-icon-delete ui-btn-icon-left">Deconnexion</a>
				</div>

				<!-- /footer -->
				<div data-role="footer" data-position="fixed">
					<div data-role="navbar">
						<ul>
							<li><a href="#param" data-role="button" data-icon="user">Paramètres</a></li>
							<li><a href="#signaler" data-role="button" data-icon="location">Signaler incident</a></li>
						</ul>
					</div>
				</div><!-- /footer -->
			</div>

			<!-- page non connecté -->
			<div data-role="page" id="pageNonConnecte">
				<div data-role="content" class="ui-content">
					<p>SafeRoad<p>
						<form name="connexion">
							<label for="info">Login:</label>
							<input name="login" id="login"></input>

							<label for="info">Mot de passe:</label>
							<input type="password" name="mdp" id="password"></input>

							<button onclick="verifUser(document.forms['connexion'].mdp.value)" class="ui-btn ui-icon-check ui-btn-icon-left">Connexion</button>
						</form>
						<a href="#" class="ui-btn ui-icon-user ui-btn-icon-left">Inscription</a>
					</div>
				</div>
			</body>
			</html>
