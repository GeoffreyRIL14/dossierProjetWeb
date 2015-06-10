<?php
	require '../modele.php';
	if (isset($_POST['l']))
	{
 		$login = $_POST['l'];
	}
    if (isset($_POST['pass']))
    {
    $mdp = $_POST['pass'];
    }
	if (getLoginUser($login) == true)
	{
        setUser($login, $mdp);
		header("localhost/saferoad/index.php#pageInscription");		
	}
	else 
	{
		echo "Erreur";		
	}
?>
