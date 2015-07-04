<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 11/06/2015
 * Time: 14:22
 */
require "modele.php";

class Commentaire {
    private $contenu;
    private $dateCreation;
    private $idCommentaire;
    private $Incident ;

    public function _construct($contenu, $dateCreation, $idCom)
    {
        //On récupère un commentaire
        if (isset($idCommentaire))
        {
            $this->$idCommentaire = $idCom;
            $commentaire = getCommentaire();
            foreach ($commentaire as $com)
            {
                $this->$contenu         = $com['description'];
                $this->$dateCreation    = $com['dateCommentaire'];
            }
        }
        //On crée un commentaire
        elseif (isset ($contenu) && isset($dateCreation))
        {
            setCommentaire($this);
        }
    }
    public function listerCommentaireIncident($idIncident)
    {

    }
}