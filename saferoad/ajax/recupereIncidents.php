<?php 
require '../modele.php';
$incidents = getIncident(43.5824367,3.8760508, 20);
var_dump($incidents);
foreach ($incidents as $incident) {
	$chaineJson = implode(",",getIncident(43.5824367,3.8760508, 20));
	$chaineJson .= "/";
}
echo $chaineJson;