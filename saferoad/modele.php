<?php
//recupère un incident MODIF ALEXANDRE
function getIncident($lattitude, $longitude, $distance)
{
    $bdd = getBdd();

    $formule="(6366*acos(cos(radians(".$lattitude."))*cos(radians(`lattitudeIncident`))*cos(radians(`longitudeIncident`)-radians(".$longitude."))+sin(radians(".$lattitude."))*sin(radians(`lattitudeIncident`))))";
    $requete = 'SELECT Incident.*, '.$formule.' AS dist'
        . ' FROM Incident'
        . ' INNER JOIN Type_incident ON(Type_incident.idType = Incident.idType)'
        . ' WHERE '.$formule.'<='.$distance.' ORDER by dist ASC';

    /*$incidents = $bdd->query($requete);*/

    $stmt = $bdd->prepare($requete);
    $stmt->execute();

    $ligne = $stmt->fetch();
    echo $ligne[0];
    // $distance est la distance en KM max choisie

    //$sql="SELECT ville,$formule AS dist FROM ville WHERE $formule<='$_GET[distance]' ORDER by dist ASC";

    //return $incidents['idIncident']->fetch(); // Accès à la première ligne de résultat
}

// ajoute un incident
function setIncident($desc, $idType)
{
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO incident'
            . '(descriptionIncident, idCredibilite, incidentEnCours,idType)'
            . ' VALUES (?,?,?,?)'); 
    $param = array($desc, 1, 1, $idType);
    $incident->execute($param);
}

//Vérifie un utlisateur
function getVerifUser($login, $mdp)
{
    $bdd = getBdd();
    $user = $bdd->prepare('SELECT *'
        . ' FROM USER'
        . ' WHERE pseudoUser=?'
        . ' AND motDePasse=?');
    $param = array($login, md5($mdp));
    $user->execute($param);
    if ($user->rowcount() > 0)
    return true; // Accès à la première ligne de résultat
    else 
    return false;
}

// ajoute un incident
function setUser($login, $mdp)
{
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO user'
            . '(pseudoUser, motDePasse)'
            . ' VALUES (?,?)'); 
    $param = array($login, md5($mdp));
    $incident->execute($param);
}

// Effectue la connexion à la BDD
// Instancie et renvoie l'objet PDO associé
function getBdd() {
    try {
 $bdd = new PDO('mysql:host=localhost;dbname=saferoad;charset=utf8', 'root',
            'daxter04@', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
    $msg = '<p>ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage()."</p>";
    die($msg);
}
  return $bdd;
}