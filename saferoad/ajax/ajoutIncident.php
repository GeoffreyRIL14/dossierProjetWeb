<?php 
require '../modele.php';
if (isset($_GET['d']))
{
 $description = $_GET['d'];
}

if (isset($_GET['t']))
{
 $idType = $_GET['t']; 
}

if (isset($_GET['lat']))
{
 $lat = $_GET['lat'];
}

if (isset($_GET['lng']))
{
 $lng = $_GET['lng']; 
}

echo $lat;
setIncident($description, $idType, $lat, $lng );

