<?php require '../modele.php';
session_start();
$l = '';
$m = '';
extract($_POST);
$login = $l;
$mdp = $m;
$idUser = array();
$idUser = getVerifUser($login, $mdp);
if ($idUser[0]['idUser'] != null) {
    $_SESSION['idUser'] = $idUser[0]['idUser'];
    echo 1;
}
else
    echo 0;