<?php
/**
 * Created by PhpStorm.
 * User: Antoine
 * Date: 11/06/2015
 * Time: 14:22
 */

class Utilisateur {
    private $nom;
    private $prenom;
    private $mail;
    private $mdp;
    private $idUtilisateur;
    private $commentaires = array();
    private $parametres;

    public function _construct($nom, $prenom, $mail, $mdp, $idUser)
    {
        //On récupère un utilisateur
        if (isset($idUser))
        {
            $this->$idUtilisateur = $idUser;
            $utilisateur = getUser();
            foreach ($utilisateur as $util)
            {
                $this->$nom             = $util['nom'];
                $this->$prenom          = $util['prenom'];
                $this->$mail            = $util['mail'];
                $this->$mdp             = $util['motDePasse'];
            }
        }
        //On crée un utilisateur
        elseif (isset ($contenu) && isset($dateCreation))
        {
        }
    }
    public function Signaler()
    {

    }

    public function Configure()
    {

    }

    public function EcrireCommentaire($contenu)
    {
        $commentaire = new Commentaire($contenu);
    }
    public function Connexion()
    {
        getVerifUser($this.mail, $this->mdp);
    }
}