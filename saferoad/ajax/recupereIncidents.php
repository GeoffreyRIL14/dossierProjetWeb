<?php 
require '../modele.php';
$incidents = getIncident(43.5824367,3.8760508, 2000);
$chaineJson = "";
foreach ($incidents as $incident) {
	$chaineJson .= implode(",",$incident);
	$chaineJson .= "/";
}
echo $chaineJson;