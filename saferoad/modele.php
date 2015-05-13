<?php
function getTypesIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM type_incident');
    return $incidents->fetchAll();
}

//recupère un incident MODIF ALEXANDRE
function getIncident($lattitude, $longitude, $distance)
{
    $bdd = getBdd();

    $formule="(6366*acos(cos(radians(".$lattitude."))*cos(radians(`lattitudeIncident`))*cos(radians(`longitudeIncident`)-radians(".$longitude."))+sin(radians(".$lattitude."))*sin(radians(`lattitudeIncident`))))";
    $requete = 'SELECT distinct incident.descriptionIncident, incident.lattitudeIncident, incident.longitudeIncident, incident.idType, type_incident.nomType,  incident.idIncident, '.$formule.' AS dist'
        . ' FROM incident'
        . ' INNER JOIN type_incident ON(type_incident.idType = incident.idType)'
        . ' WHERE '.$formule.'<='.$distance.' ORDER by dist ASC';

    /*$incidents = $bdd->query($requete);*/

    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
}

// ajoute un incident
function setIncident($desc, $idType,$lat, $lng)
{
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO incident'
            . '(descriptionIncident, idCredibilite, incidentEnCours,idType, lattitudeIncident, longitudeIncident, dateHeureIncident)'
            . ' VALUES (?,?,?,?,?,?,NOW())'); 
    $param = array($desc, 1, 1, $idType, $lat, $lng);
    $incident->execute($param);
}

//Vérifie un utlisateur
function getVerifUser($login, $mdp)
{
    $bdd = getBdd();
    $user = $bdd->prepare('SELECT *'
        . ' FROM user'
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
            '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
    $msg = '<p>ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage()."</p>";
    die($msg);
}
  return $bdd;
}