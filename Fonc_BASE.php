<?php
// Fonctions d'acces a la base de donnees
//---------------------------------------------------
function ouvrir_base ()
{	
  if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{ 
	$host="localhost"; //Nom de l'hôte mysql     
    $db="video_test"; //Nom de botre base de données
	$user="root"; //Nom d'utilisateur de la base de données
	$pw=""; //Mot de passe de votre base
	}
	else
	{	
	$host="sql2.olympe.in"; //Nom de l'hôte mysql     
    $db="bqt9rm0v"; //Nom de botre base de données
	$user="bqt9rm0v"; //Nom d'utilisateur de la base de données
	$pw="video071466"; //Mot de passe de votre base
	}
	

$connect=mysqli_connect($host,$user,$pw, $db);

/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
}


  return $connect;
}

//---------------------------------------------------
function fermer_base ($db)
{
  mysqli_close($db);
}

?>