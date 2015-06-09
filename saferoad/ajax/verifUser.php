<?php require '../modele.php';
session_start();
$l = '';
$m = '';
extract($_POST);
$login = $l;
$mdp = $m;
$Memorise = $mem;
$idUser = array();
$idUser = getVerifUser($login, $mdp);
if ($idUser[0]['idUser'] != null) {
    $_SESSION['idUser'] = $idUser[0]['idUser'];
    if ($Memorise == 1)
    {
        $salt = 'SHIFLETT';
        $identifier = md5($salt . md5($login . $salt));
        $token = md5(uniqid(rand(), TRUE));
        $timeout = time() + 60 * 60 * 24 * 7;
        setcookie('auth', "$identifier:$token", $timeout);
        setIdentifier($idUser[0]['idUser'], $identifier);
    }
    echo 1;

}
else
    echo 0;