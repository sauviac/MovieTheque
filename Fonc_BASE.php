<?php
// Fonctions d'acces a la base de donnees
//---------------------------------------------------
function ouvrir_base ()
{	
  if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{ 
	$host="localhost"; //Nom de l'h�te mysql     
    $db="video_test"; //Nom de botre base de donn�es
	$user="root"; //Nom d'utilisateur de la base de donn�es
	$pw=""; //Mot de passe de votre base
	}
	else
	{	
	$host="sql2.olympe.in"; //Nom de l'h�te mysql     
    $db="bqt9rm0v"; //Nom de botre base de donn�es
	$user="bqt9rm0v"; //Nom d'utilisateur de la base de donn�es
	$pw="video071466"; //Mot de passe de votre base
	}
	

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