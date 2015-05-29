<?php
function getTypesIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM Type_incident');
    return $incidents->fetchAll();
}
function getSeuilDistanceMax($idUser)
{
    $bdd = getBdd();
    $distance = $bdd->prepare('SELECT seuilDistanceMax'
        . ' FROM USER'
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
        . ' INNER JOIN Type_incident ON(Type_incident.idType = incident.idType)'
        . ' WHERE '.$formule.'<='.$distance.' ORDER by dist ASC';


    $stmt = $bdd->prepare($requete);
    $stmt->execute();
    $lignes = $stmt->fetchall(PDO::FETCH_ASSOC);
    return $lignes;
}

function getLibelleTypeIncident($idType)
{
    $bdd = getBdd();
    $requete = 'SELECT nomType WHERE idType = ?';
    $stmt= $bdd->prepare($requete);
    $param = array($idType);
    $stmt->execute($param);
    return $stmt;
}
// ajoute un incident
function setIncident($desc, $idType,$lat, $lng)
{
    $bdd = getBdd();
    $incident = $bdd->prepare('INSERT INTO incident'
            . '(descriptionIncident, idCredibilite, incidentEnCours,idType, lattitudeIncident, longitudeIncident)'
            . ' VALUES (?,?,?,?,?,?)'); 
    $param = array($desc, 1, 1, $idType, $lat, $lng);
    $incident->execute($param);
}

//Vérifie un utlisateur
function getVerifUser($login, $mdp)
{
    $bdd = getBdd();
    $user = $bdd->prepare('SELECT idUser'
        . ' FROM USER'
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
    $user = $bdd->prepare('SELECT * FROM USER WHERE pseudoUser = ?');
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
    $stmt = $bdd->prepare('SELECT enableNotif, seuilCredibMin, seuilDistanceMax FROM USER WHERE idUser = ?');
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
    $requete = 'Update USER'
        . ' SET seuilCredibMin = ?,'
        . ' seuilDistanceMax = ?,'
        . ' enableNotif = ?'
        . ' WHERE idUser = ?';
    $stmt = $bdd->prepare($requete);
    $param = array($seuilCredib, $seuilDistance, $notif, $idUser);
    $stmt->execute($param);



//    $bdd = getBdd();
//    $paramUser = $bdd->prepare('Update USER'
//        . 'SET seuilCredibMin = ?'
//        . 'SET seuilDistanceMax = ?'
//        . 'SET enableNotif = ?'
//        . 'WHERE idUser = ?');
//    $param = array($seuilCredib, $seuilDistance, $notif, $idUser);
//    $paramUser->execute($param);
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