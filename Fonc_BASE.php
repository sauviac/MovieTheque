<?php
// Fonctions d'acces a la base de donnees
//---------------------------------------------------
function ouvrir_base ()
{
       $host="localhost"; //Nom de l'h�te mysql
//       $host="ist-pilat.univ-st-etienne.fr"; //Nom de l'h�te mysql
       
       $db="video"; //Nom de botre base de donn�es
	
	$user="root"; //Nom d'utilisateur de la base de donn�es
	$pw=""; //Mot de passe de votre base
	

$connect=mysqli_connect($host,$user,$pw, $db);

/* V�rification de la connexion */
if (mysqli_connect_errno()) {
    printf("�chec de la connexion : %s\n", mysqli_connect_error());
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