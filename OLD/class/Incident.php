<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 11/06/2015
 * Time: 14:20
 */
require "modele.php";
class Incident {
    private $latitude;
    private $longitude;
    private $nbPoints;
    private $description;
    private $dateDescription;
    private $type;
    private $parametres;

    public function _construct($latitude, $longitude, $description, $type, $idIncident)
    {
        //On recupere l'incident
        if (isset($idIncident))
        {
            //Recup incident
        }
        else
        {
            setIncident($description, $type, $latitude, $longitude);
        }
    }

    public function Initialiser()
    {
    getIncident($this);
    }
}