<?php require '../modele.php';
if (isset($_GET['l']))
{
 $login = $_GET['l'];
}

if (isset($_GET['m']))
{
 $mdp = $_GET['m']; 
}
if (getVerifUser($login, $mdp))
	header("localhost/saferoad/index.php#pageConnecte");
else 
echo "Erreur";