<?php require '../modele.php';
if (isset($_POST['l']))
{
 $login = $_POST['l'];
}

if (isset($_POST['m']))
{
 $mdp = $_POST['m']; 
}
if (getVerifUser($login, $mdp))
	header("localhost/saferoad/index.php#pageNonConnecte");
else 
echo "Erreur";