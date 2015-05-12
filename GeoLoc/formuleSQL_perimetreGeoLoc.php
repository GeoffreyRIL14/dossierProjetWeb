<?php

$lat_courante=$_GET['lat']; 		// ou $_POST
$lon_courante=$_GET['lon']; 		// ou $_POST
$distance_choisie=$GET['distance']; // ou $_POST

$formule="(6366*acos(cos(radians($lat_courante))*cos(radians(`lat`))*cos(radians(`lon`) -radians($lon_courante))+sin(radians($lat_courante))*sin(radians(`lat`))))";

// remarque : lat et lon sont les colonnes de la table "concerts" (à renommer selon votre projet)


$sql="SELECT ville,$formule AS dist FROM Concert WHERE $formule<=$distance_choisie ORDER by dist ASC";


?>

