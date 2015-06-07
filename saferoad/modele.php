<?php
// ---------------------------requête permettant de recuperer les types d'incident ---------------------------------------------------

function getTypesIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM type_incident');
    return $incidents->fetchAll();
}
// ---------------------------/requête permettant de recuperer les types d'incident ---------------------------------------------------

// ---------------------------requête permettant de maj la crédibilité ---------------------------------------------------

function setCredibilite($notation, $idIncident)
{
    $bdd = getBdd();
    $requete = 'Update incident'
        . ' SET nbPoint = ?'
        . ' WHERE idIncident = ?';
    $stmt = $bdd->prepare($requete);
    $param = array($notation,$idIncident);
    $stmt->execute($param);
}
// ---------------------------/requête permettant de maj la crédibilité ---------------------------------------------------

// ---------------------------requête permettant de verifier qu'un user n'a pas déja noté ---------------------------------------------------

function getNoter($idUser, $idIncident)
{
    $bdd = getBdd();
    $ANoter = $bdd->prepare('SELECT idUser'
        . ' FROM noter'
        . ' WHERE idUser=?'
        . ' AND idIncident=?');
    $param = array($idUser, $idIncident);
    $ANoter->execute($param);
    $ligne = $ANoter->fetch();
    if ($ANoter->rowcount() == 0) {
        return 0;
    }
    else
        return 1;
}

// ---------------------------/requête permettant de verifier qu'un user n'a pas déja noté ---------------------------------------------------

// ---------------------------requête permettant d'ajouter un utilisateur et un incident dans la table noter ---------------------------------------------------

function setNoter($idUser, $idIncident)
{
    $bdd = getBdd();
    $noter = $bdd->prepare('INSERT INTO noter'
        . '(idUser, idIncident)'
        . ' VALUES (?,?)');
    $param = array($idUser, $idIncident);
    $noter->execute($param);
}

// ---------------------------/requête permettant d'ajouter un utilisateur et un incident dans la table noter ---------------------------------------------------

// ---------------------------requête permettant de recuperer le nombre de point de cred d'un incident ---------------------------------------------------

function getCredibilite($idIncident)
{
    $bdd = getBdd();
    $credibilite = $bdd->prepare('SELECT nbPoint'
        . ' FROM incident'
        . ' WHERE idIncident = ?');
    $param = array($idIncident);
    $credibilite->execute($param);
    return $credibilite->fetch();
}
// ---------------------------/requête permettant de recuperer le nombre de point de cred d'un incident ---------------------------------------------------

// ---------------------------requête permettant de recuperer le seuil de distance max ---------------------------------------------------

function getSeuilDistanceMax($idUser)
{
    $bdd = getBdd();
    $distance = $bdd->prepare('SELECT seuilDistanceMax'
        . ' FROM user'
        . ' WHERE idUser=?');
    $param = array($idUser);
    $distance->execute($param);
    return $distance->fetch();
}
// --------------------------- /requête permettant de recuperer le seuil de distance max ---------------------------------------------------

//function getLibelleCredibilite($notation)
//{
//    $bdd = getBdd();
//    $credibilite = $bdd->prepare('SELECT libelleCredibilite'
//        . ' FROM credibilite'
//        . ' WHERE valeurCredibilite = ROUND(?)');
//    $param = array($notation);
//    $credibilite->execute($param);
//    return $credibilite->fetch();
//}

// --------------------------- requête permettant de recuperer les incidents ---------------------------------------------------

function getIncident($lattitude, $longitude, $distance)
{
    $bdd = getBdd();
    $formule="(6366*acos(cos(radians(".$lattitude."))*cos(radians(`lattitudeIncident`))*cos(radians(`longitudeIncident`)-radians(".$longitude."))+sin(radians(".$lattitude."))*sin(radians(`lattitudeIncident`))))";
    $requete = 'SELECT distinct incident.descriptionIncident, incident.lattitudeIncident, incident.longitudeIncident, incident.idType,type_incident.nomType, incident.idIncident, incident.nbPoint,' .$formule.' AS dist'
        . ' FROM incident'
        . ' INNER JOIN type_incident ON(type_incident.idType = incident.idType)'
        . ' WHERE '.$formule.'<='.$distance.' ORDER by dist ASC';


    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
}
// --------------------------- /requête permettant de recuperer les incidents ---------------------------------------------------

//function getLibelleTypeIncident($idType)
//{
//    $bdd = getBdd();
//    $requete = 'SELECT nomType from incident WHERE idType = ?';
//    $stmt= $bdd->prepare($requete);
//    $param = array($idType);
//    $stmt->execute($param);
//    return $stmt;
//}

// --------------------------- requête permettant d'ajouter un incident ---------------------------------------------------
function setIncident($desc, $idType,$lat, $lng)
{
    $date = date("Ymd");
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO incident'
            . '(descriptionIncident, idCredibilite, incidentEnCours,idType, lattitudeIncident, longitudeIncident,dateHeureIncident)'
            . ' VALUES (?,?,?,?,?,?,?)');
    $param = array($desc, 1, 1, $idType, $lat, $lng, $date);
    $incident->execute($param);
}
// --------------------------- /requête permettant d'ajouter un incident ---------------------------------------------------

// --------------------------- requête permettant de verifier l'existance d'un user ---------------------------------------------------

function getVerifUser($login, $mdp)
{
    $bdd = getBdd();
    $user = $bdd->prepare('SELECT idUser'
        . ' FROM user'
        . ' WHERE pseudoUser=?'
        . ' AND motDePasse=?');
    $param = array($login, $mdp);
    $user->execute($param);
    $ligne = $user->fetchall(PDO::FETCH_ASSOC);
    if ($user->rowcount() == 0) {
        $ligne = null;
    }
        return $ligne;
}

// --------------------------- /requête permettant de verifier l'existance d'un user ---------------------------------------------------

// --------------------------- requête permettant de verifier  si le login existe ---------------------------------------------------

function getLoginUser($login)
{
    $bdd = getBdd();
    $user = $bdd->prepare('SELECT * FROM user WHERE pseudoUser = ?');
    $param = array($login);
    $user->execute($param);

    if($user->rowcount() == 0)
    {
        return true;/*pseudo disponible*/
    }
    else
    {
        return false;/*pseudo indisponible*/
    }
}
// --------------------------- /requête permettant de verifier  si le login existe ---------------------------------------------------

// --------------------------- requête permettant de récupèrer les param de l'utilisateur---------------------------------------------------

function getParam($idUser)
{
    $bdd = getBdd();
    $stmt = $bdd->prepare('SELECT enableNotif, seuilCredibMin, seuilDistanceMax FROM user WHERE idUser = ?');
    $param = array($idUser);
    $stmt->execute($param);
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
}
// --------------------------- /requête permettant de récupèrer les param de l'utilisateur---------------------------------------------------

// --------------------------- requête permettant d'ajouter un user---------------------------------------------------

function setUser($login, $mdp)
{
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO user'
            . '(pseudoUser, motDePasse)'
            . ' VALUES (?,?)'); 
    $param = array($login, md5($mdp));
    $incident->execute($param);
}
// --------------------------- /requête permettant d'ajouter un user---------------------------------------------------

// --------------------------- requête permettant de mettre a jour les param---------------------------------------------------

function setParamUser($idUser, $seuilCredib, $seuilDistance, $notif)
{
    $bdd = getBdd();
    $requete = 'Update user'
        . ' SET seuilCredibMin = ?,'
        . ' seuilDistanceMax = ?,'
        . ' enableNotif = ?'
        . ' WHERE idUser = ?';
    $stmt = $bdd->prepare($requete);
    $param = array($seuilCredib, $seuilDistance, $notif, $idUser);
    $stmt->execute($param);
}

// --------------------------- /requête permettant de mettre a jour les param---------------------------------------------------


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