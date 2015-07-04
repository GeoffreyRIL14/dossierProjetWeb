<?php 
require '../modele.php';
session_start();
if (isset($_GET['inc']))
{
	$idIncident = $_GET['inc'];
}

$commentaires = getcommentaires($idIncident);
$chaineJson = "";

foreach ($commentaires as $commentaire) {
    $chaineJson .= implode(",",$commentaire);
    $chaineJson .= "/";
}
echo $chaineJson;