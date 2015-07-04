<?php 
require '../modele.php';
if (isset($_GET['c']))
{
 $commentaire = $_GET['c'];
}

if (isset($_GET['i']))
{
 $idInci = $_GET['i']; 
}

if (isset($_GET['u']))
{
 $user = $_GET['u']; 
}

ajoutCommentaire($commentaire, $idInci, $user);