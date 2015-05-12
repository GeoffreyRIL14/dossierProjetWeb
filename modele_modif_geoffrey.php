<?php
//recupère un incident
function getIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM Incident'
        . ' INNER JOIN Type_incident ON(Type_incident.idType = Incident.idType)');
    return $incidents->fetch(); // Accès à la première ligne de résultat
}

//recupère tous les types d'incidents
/* modif Geoffrey */
function getTypesIncident()
{
    $bdd = getBdd();
    $incidents = $bdd->query('SELECT *'
        . ' FROM Type_incident');
    return $incidents->fetchAll();
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
            '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
    $msg = '<p>ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage()."</p>";
    die($msg);
}
  return $bdd;
}