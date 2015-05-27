<?php require '../modele.php';
$l = '';
$m = '';
extract($_POST);
$login = $l;
$mdp = $m;
if (getVerifUser($login, $mdp) ) {
    echo 1;
}
else
    echo 0;