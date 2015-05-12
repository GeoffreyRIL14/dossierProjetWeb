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
	/*	setInterval("recupereIncidents()",1000)*/

	</script>
	<script type="text/javascript">
	var map ;
	var pos ;
	var bAjoutIncident = 0;
		//requête ajax permettant d'ajouter un nouvel incident
		function ajoutIncident(lat, lng)
		{
			var description = document.forms['incident'].desc.value;
			var idType = document.forms['incident'].typeIncident.value;
			$.ajax({
				type: 'GET',
				url: './ajax/ajoutIncident.php',
				data: '&d=' + description + '&t=' + 1 +'&lat=' + lat + '&lng=' + lng,  
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
				type: 'POST',
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

		//fonction JS recupérant la lattitute et longitude
		function recuperePos(bAjoutInc)
		{
			bAjoutIncident = bAjoutInc;
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(
					geo_ok,
					geo_error, 
					{ enableHighAccuracy:true, maximumAge:5000, timeout:5000}
					);
			} else {
				alert('Erreur : pas de support de la géolocalisation dans votre navigateur');
			}
		}

		function geo_ok(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			if (!bAjoutIncident)
				Initialisation(latitude,longitude);
			else
				ajoutIncident(latitude, longitude);
				bAjoutIncident = 0;

		}

		function geo_error(error) {
			alert(error.message+" / "+error.code);	 
		}

		//Initialisation de la carte
		function Initialisation(latitude,longitude) {
			pos = new google.maps.LatLng(latitude, longitude);
			        var myOptions = {
				            zoom: 10,
				            center: pos,
				            mapTypeId: google.maps.MapTypeId.ROADMAP
			        };
			map = new google.maps.Map(document.getElementById("map"), myOptions);
			ajoutMarqueur(pos,"");       
		}

		//Ajoute UN marqueur et affiche la description de l'incident sur le click
		function ajoutMarqueur(desc)
		{
			var optionsMarqueur = {
				position: pos,
				map: map,
			}
			var marqueur = new google.maps.Marker(optionsMarqueur);
			var contenuInfoBulle = 'Je suis ici';
			var infoBulle = new google.maps.InfoWindow({
				content: contenuInfoBulle
			})
			google.maps.event.addListener(marqueur, 'click', function() {
				infoBulle.open(map, marqueur);
			});
		}
		recuperePos(0);
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
							<?php
						/* modif Geoffrey */
						$listeIncidents = array();
						$listeIncidents = getTypesIncident();

						foreach ($listeIncidents as $ligne) {
							echo '<option value="' . $ligne['idType'] . '">' . $ligne['nomType'] . '</option>';
							# code...
						}

/*						<option value="acc">Accident</option>
						<option value="anm">Animal mort</option>
						<option value="rad">Radar</option>*/
						?>
						</optgroup>
					</select>

					<label for="info">description</label>
					<textarea name="desc" id="description" style="height:40%"></textarea>
				</form>
				<!-- MODIF ANTOINE -->
				<a href="#myPopup" data-rel="popup" onclick = "recuperePos(1);"  class="ui-btn ui-btn-inline ui-corner-all ui-shadow" style="margin: auto; ">Envoyer</a>
				<!-- MODIF ANTOINE -->
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
