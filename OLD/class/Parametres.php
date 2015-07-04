<?php
require 'modele.php';
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 11/06/2015
 * Time: 14:21
 */

class Parametres {
    private $seuilCredibilite;
    private $seuilDistance;
    private $notification;
    private $incident;

    public function _construct($seuilCredibilite,$seuilDistance, $notification, $idUser)
    {
        //On recupere les parametres
        if(isset($idUser))
        {
            $parametres = getParam($idUser);
            foreach ($parametres as $parametre)
            {
                $this->$seuilCredibilite    = $parametre['seuilCredibMin'];
                $this->$seuilDistance       = $parametre['seuilDistanceMax'];
                $this->$notification        = $parametre['enableNotif'];
            }
        }
        else
        {
            setParamUser($idUser, $this->$seuilCredibilite, $this->$seuilDistance , $this->$notification);
        }

    }
}