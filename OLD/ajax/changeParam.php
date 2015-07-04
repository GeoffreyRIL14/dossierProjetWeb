<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 27/05/2015
 * Time: 19:11
 */
require '../modele.php';
$id = '';
$dist = '';
$cred = '';
$not = '';
extract($_POST);
$idUser         = $id;
$distance       = $dist;
$credibilite    = $cred;
$notification   = $not;
setParamUser($idUser, $credibilite, $distance, $notification);