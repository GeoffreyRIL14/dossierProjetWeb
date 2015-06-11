<?php
session_start();
require '../modele.php';
$idUser = $_SESSION['idUser'];
$param = getParam($idUser);
$chaineJson = "";
foreach ($param as $parametre) {
    $chaineJson .= implode(",",$parametre);
}
echo $chaineJson;