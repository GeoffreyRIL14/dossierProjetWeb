<?php
function getTypesIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM type_incident');
    return $incidents->fetchAll();
}

function setCredibilite($idIncident)
{
    $bdd = getBdd();
    $requete = 'Update user'
        . ' SET seuilCredibMin = ?,'
        . ' seuilDistanceMax = ?,'
        . ' enableNotif = ?'
        . ' WHERE idUser = ?';
    $stmt = $bdd->prepare($requete);
    $param = array(getCredibilite($idIncident)+1);
    $stmt->execute($param);
}

function getCredibilite($idIncident)
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM type_incident');
    return $incidents->fetch();
}


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
//recupère un incident MODIF ALEXANDRE
function getIncident($lattitude, $longitude, $distance)
{
    $bdd = getBdd();
    $formule="(6366*acos(cos(radians(".$lattitude."))*cos(radians(`lattitudeIncident`))*cos(radians(`longitudeIncident`)-radians(".$longitude."))+sin(radians(".$lattitude."))*sin(radians(`lattitudeIncident`))))";
    $requete = 'SELECT distinct incident.descriptionIncident, incident.lattitudeIncident, incident.longitudeIncident, incident.idType,type_incident.nomType, incident.idIncident,' .$formule.' AS dist'
        . ' FROM incident'
        . ' INNER JOIN type_incident ON(type_incident.idType = incident.idType)'
        . ' WHERE '.$formule.'<='.$distance.' ORDER by dist ASC';


    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
}

function getLibelleTypeIncident($idType)
{
    $bdd = getBdd();
    $requete = 'SELECT nomType from incident WHERE idType = ?';
    $stmt= $bdd->prepare($requete);
    $param = array($idType);
    $stmt->execute($param);
    return $stmt;
}
// ajoute un incident
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

//Vérifie un utlisateur
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

/*Vérifie si le login existe (à integrer)*/
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

/*Récupère les param de l'utilisateur*/
function getParam($idUser)
{
    $bdd = getBdd();
    $stmt = $bdd->prepare('SELECT enableNotif, seuilCredibMin, seuilDistanceMax FROM user WHERE idUser = ?');
    $param = array($idUser);
    $stmt->execute($param);
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
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