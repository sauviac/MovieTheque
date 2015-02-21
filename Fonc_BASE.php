<?php
// Fonctions d'acces a la base de donnees
//---------------------------------------------------
function ouvrir_base ()
{
       $host="localhost"; //Nom de l'hôte mysql
//       $host="ist-pilat.univ-st-etienne.fr"; //Nom de l'hôte mysql
       
       $db="video"; //Nom de botre base de données
	
	$user="root"; //Nom d'utilisateur de la base de données
	$pw=""; //Mot de passe de votre base
	

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