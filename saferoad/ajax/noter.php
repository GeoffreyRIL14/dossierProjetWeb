<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 27/05/2015
 * Time: 19:11
 */
require '../modele.php';
$idUser = '';
$ajout = '';
extract($_POST);
$idUser         = $idU;
$ajout          = $a;
$idIncident      = $idI;

//N'a pas déjà noter
if (!getNoter($idUser, $idIncident)) {
    //Notation++
    if ($a == 1) {
        $nbPoint = getCredibilite($idIncident);
        $nbPoint = $nbPoint[0];
        $nbPoint += 1;
        setCredibilite($nbPoint, $idIncident);
        setNoter($idUser, $idIncident);
        echo "1";
    } //Notation --
    else {
        $nbPoint = getCredibilite($idIncident);
        $nbPoint = $nbPoint[0];
        $nbPoint -= 1;
        setCredibilite($nbPoint, $idIncident);
        setNoter($idUser, $idIncident);
        echo "1";
    }
}
else
{
    echo "Vous avez déjà noter cet incident";
}
