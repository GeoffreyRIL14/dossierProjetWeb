<?php
require '../modele.php';
session_start();
extract($_POST);
$idIncident = $idInc;
$idUser = $_SESSION['idUser'];
setMasque($idIncident, $idUser);