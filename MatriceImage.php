<?php

// réalisation d'un mur d'image a partier de la base de données
// 1 // Connexion base
require_once("./Fonc_BASE.php");
include("./RedimPict.php");
$db= ouvrir_base ();

// 2 // requetes SQL
$table="movie";


// 2B // Choix nombre de colonnes du tableau
$n_Colonnes = 10;
// 2C // Choix nombre de lignes du tableau
$n_Lignes = 7;
$n_Total=$n_Colonnes * $n_Lignes ;

// 2A // Comptage du nombre de film
$RequeteSQL="select count(*) as NMAX FROM $table"; 
$Result=mysqli_query($db,$RequeteSQL);      
if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
$Fiche = mysqli_fetch_assoc($Result) ;
$Nmax=$Fiche['NMAX'];
$No=rand(1, $Nmax-$n_Total-1);
mysqli_free_result($Result);


// 2D // Recup des films et images associées 
$RequeteSQL="select id_film, image FROM $table Where id_film>=$No limit $n_Total"; 
$Result=mysqli_query($db,$RequeteSQL);      
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
 $rowcount=mysqli_num_rows($Result);
 
 
// 3 // Exploitation des resultats de la requete


// 3A // Creation du tableau
  // debut tableau bootstap
	echo "<table class=\"table-condensed\"><tbody>";
	
	// boucle sur films	
	
	 
		 for ($i=1 ; $i<=$n_Lignes ; $i++) 
		{
			 echo "<tr>";
			 for ($j=1; $j<=$n_Colonnes ; $j++)
			 {
				 $Fiche = mysqli_fetch_assoc($Result) ;
				 $id=$Fiche['id_film'];
				 $Limage=$Fiche['image']; 
				 if ( empty($Limage) ) {$Limage="bobine.jpg";} 
				 $Limage="Illustrations/".$Limage;
				echo "<th>";
				echo "<a href=\"MovieFiche.php?id=$id\">";
				echo "<img class=\"img-responsive\" style=\"width: 100%; max-width:50px\" src=\"$Limage\">";
				echo "</a></th>";
			 }
			 echo "</tr>";
		}
	 
	 
	
	
	// fin tableau bootstap
	echo "</tbody></table>";
			
			
?>