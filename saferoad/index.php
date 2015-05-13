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
	<style type="text/css">
	
	#map
	{
		width:100%;
		height:300px;
	}
	</style>
	<script type="text/javascript">
	/*	setInterval("recupereIncidents()",1000)*/

	function insereNotification(idNotification, typeIncident, descriptionIncident)
	{
		var idJQNotif = '#notif_' + idNotification;

		if ($(idJQNotif).length <= 0) {

			$('#notif_0').before('<div class = \"notification\" data-role = \"collapsible" id = \"notif_' + idNotification + '\">' +
				'<h2> ' + typeIncident + ' </h2>' +
				'<p> ' + descriptionIncident + ' </p>' +
				'<button class=\"ui-btn ui-btn-inline ui-mini\">Editer</button>' +
				'<button class=\"ui-btn ui-btn-inline ui-mini\" onclick=\"$(\'#notif_' + idNotification + '\').hide()\">Masquer</button>' +
				'</div>');

			$('#main').collapsibleset();
		}
	}

	/////////////////////////////

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
				//url: './ajax/ajoutIncident.php?d=' + description + '&t=' + 1 +'&lat=' + lat + '&lng=' + lng,  
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
					//Chaque incident
					var incident = data.split("/");	
					for (var i = incident.length - 1; i >= 0; i--) {
						//chaque argument d'incident	
						var param = incident[i].split(",");
						var description = param[0];
						var lat = param[1];
						var lng = param[2];
						var idType = param[3];
						var nomType = param[4];
						var idIncident = param[5];
						if (lat != null) {
							ajoutMarqueur(description, lat, lng, idType);
							insereNotification(idIncident, nomType, description);
						}

					};

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
					{ enableHighAccuracy:true, maximumAge:5000, timeout:50000}
					);
			} else {
				alert('Erreur : pas de support de la géolocalisation dans votre navigateur');
			}
		}

		function geo_ok(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			if (!bAjoutIncident)
			{
				Initialisation(latitude,longitude);
			}
			else
			{
				ajoutIncident(latitude, longitude);
			}

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
			ajoutMarqueur();  
		}

		//Ajoute UN marqueur et affiche la description de l'incident sur le click
		function ajoutMarqueur(desc, lat, lng, idType)
		{
			var urlImage; 
			if (lat != null)
			{	
				pos = new google.maps.LatLng(lat, lng);
			}

			if(idType == 1)
			{
				urlImage = "./images/iconeTravaux.png";
			}
			if(idType == 2)
			{
				urlImage = "./images/iconeRadar.png";
			}

			if(idType == 3)
			{
				urlImage = "./images/iconePolice.png";
			}
			if(idType == 4)
			{
				urlImage = "./images/iconeAccident.png";
			}
			if (urlImage != null)
			{
			var imageMarqueur = {
				url: urlImage,
				size: new google.maps.Size(25, 25),
				anchor: new google.maps.Point(25, 25)
			};
			var optionsMarqueur = {
				position: pos,
				map: map,
				icon: imageMarqueur,
			};
			}
			else
			{
				var optionsMarqueur = {
				position: pos,
				map: map,
			};
			}
			
			var marqueur = new google.maps.Marker(optionsMarqueur);
			var contenuInfoBulle = desc;
			var infoBulle = new google.maps.InfoWindow({
				content: contenuInfoBulle
			})
			google.maps.event.addListener(marqueur, 'click', function() {
				infoBulle.open(map, marqueur);
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
			<a href="#pageConnecte" class="ui-btn ui-btn-inline ui-corner-all ui-shadow" data-rel="close" style="margin: auto; ">retour</a>
		</div>

		<div data-role="panel" id="param"> 
			<h2>Modifier les paramètres utilisateurs</h2>
			<p>blablabla..</p>

			<a href="#pageConnecte" class="ui-btn ui-btn-inline ui-corner-all ui-shadow" data-rel="close" style="margin: auto; ">retour</a>
		</div>

		<div data-role="popup" id="myPopup">
			<p>Alerte ajoutée</p>
		</div>

		<div id="main" role="main" data-role="content" class="ui-content">
			<p>SafeRoad<p>
				<p>Parce que nous qu'on aime bien les routes sures<p>

					<div class = "notification" data-role = "collapsible" id = "notif_0" hidden>
						<h2> vide </h2>
						<p> vide </p>
						<button class="ui-btn ui-btn-inline ui-mini">Editer</button>
						<button class="ui-btn ui-btn-inline ui-mini" onclick="$('#notif_0').hide()">Masquer</button>
					</div>

					<div id="map"></div>
					<a href="#pageNonConnecte" class="ui-btn ui-icon-delete ui-btn-icon-left">Deconnexion</a>
				

				<!-- /footer -->
				<div data-role="footer" data-position="fixed">
					<div data-role="navbar">
						<ul>
							<li><a onclick ="recupereIncidents();"href="#param" data-role="button" data-icon="user">Paramètres</a></li>
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
				<script type="text/javascript">recuperePos(0);</script>
			</body>
			</html>
