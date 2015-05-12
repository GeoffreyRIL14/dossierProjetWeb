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

setIncident($description, $idType );

