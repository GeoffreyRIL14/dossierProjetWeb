<?php 
require '../modele.php';
session_start();
$idUser = $_SESSION['idUser'];
$distance = intval(getSeuilDistanceMax($idUser)) ;
$incidents = getIncident(43.5824367,3.8760508, $distance);
$chaineJson = "";
foreach ($incidents as $incident) {
	$chaineJson .= implode(",",$incident);
	$chaineJson .= "/";
}
echo $chaineJson;