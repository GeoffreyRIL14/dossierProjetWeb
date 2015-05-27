<?php 
require '../modele.php';
//$distance = getSeuilDistanceMax();
$distance = 2000;
$incidents = getIncident(43.5824367,3.8760508, $distance);
$chaineJson = "";
foreach ($incidents as $incident) {
	$chaineJson .= implode(",",$incident);
	$chaineJson .= "/";
}
echo $chaineJson;