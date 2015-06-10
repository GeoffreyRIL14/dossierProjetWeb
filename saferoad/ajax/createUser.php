<?php
	require '../modele.php';
	if (isset($_POST['l']))
	{
 		$login = $_POST['l'];
	}

	if (getLoginUser($login) == true)
	{
		header("localhost/saferoad/index.php#pageInscription");		
	}
	else 
	{
		echo "Erreur";		
	}
?>
