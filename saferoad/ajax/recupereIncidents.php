<?php 
require '../modele.php';
session_start();
extract($_POST);
$lat = $lat;
$long = $lng;
$idUser = $_SESSION['idUser'];
$distanceA = getSeuilDistanceMax(1) ;
$distance = intval($distanceA[0]);
$incidents = getIncident($lat,$long, $distance, $idUser);
$incidentsToHide = getHidden($idUser);
$chaineJson = "";

foreach ($incidents as $incident) {
    $chaineJson .= implode(",",$incident);
    foreach ($incidentsToHide as $key => $incidentToHide) {
        if(in_array($incident['idIncident'], $incidentToHide))
            $chaineJson .= "," . "Hide";
    }
    $chaineJson .= "/";
}
echo $chaineJson;