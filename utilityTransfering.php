<?php 

// Transfer des résumé entrre base de données pour reparer la recup


	$host="localhost"; //Nom de l'hôte mysql     
	$user="root"; //Nom d'utilisateur de la base de données
	$pw=""; //Mot de passe de votre base
    

	$database="video"; //Nom de botre base de données
	$dbsource=mysqli_connect($host,$user,$pw, $database);
	
	$database="videobs"; //Nom de otre base de données
	$dbcible=mysqli_connect($host,$user,$pw, $database);
	

	$update=$_REQUEST['update'];

	
$table="movie";
 // ### Recup des infos film #####
 $RequeteSQL="select * FROM $table "; 
 $Result=mysqli_query($dbcible,$RequeteSQL);      
 if (!$Result) { die('Invalid query: ' . mysqli_error($dbcible)); } 


// AFFICHAGE DES INFOS SOUS DIVERSES FORMES

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html lang="fr">

	<?php
		include('entete.html');
	?>

	<body bgcolor="#ffffff">

		<?php
		include('navigationConsult.html');
		?>
<!--/.BARRE DE MENU -->	
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">VideoBS</a>
        </div>
		
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="index.php">Home</a></li>
            <li><a href="MovieSelect.php">Consulter</a></li>
            <li><a href="#contact">G&eacute;rer</a></li>
			<li>&nbsp;&nbsp;&nbsp;</li>
			<li><a href="MovieFiche.php?id=<?php echo $IdPrev ;?>"><span class="label label-primary">	&laquo;Prev.</span></a></li>
			<li><a href="MovieFiche.php?id=<?php echo $IdNext ;?>"><span class="label label-primary">Next	&raquo;</span></a></li>
			
          </ul>
        </div>
      </div>
    </nav>	
	<!--/.BARRE DE MENU -->	
		<div class="container-fluid">
			<div class="row">
			<div class="col-xs-12">
			
			
			<?php 
			while ( $Fiche = mysqli_fetch_assoc($Result) )  // affiche les resultats issus de videobs (base CIBLE)
			{
			$Titre=$Fiche['titre'];
			$Resume=$Fiche['resume'];
			$id_film=$Fiche['id_film'];
			echo "$id_film   $Titre";
			echo "</br>";
			echo "<h6>".$Resume."</h6>";
			// Requete sur source : recherche du resume
			 $RequeteSQLsource="select * FROM $table where id_film=$id_film and titre=\"$Titre\" "; 
			 $Result_source=mysqli_query($dbsource,$RequeteSQLsource);      
			 if (!$Result_source) { die('Invalid query: ' . mysqli_error($dbsource)); } else {$Compte= mysqli_num_rows($Result_source) ;}
			 $Fiche_source = mysqli_fetch_assoc($Result_source);
				$Titre_source=$Fiche_source['titre'];
				$Resume_source=$Fiche_source['resume'];
				$id_film_source=$Fiche_source['id_film'];
					echo "$id_film_source   $Titre_source";
					echo "<h6>".$Resume_source."</h6>";
				if (strlen($Resume_source)==0 ) {echo "*******</br>";}
				
					
			
			if ($update=="yes" and $Compte<>0 and strlen($Resume_source) <> 0 ) 
			{ 
		
			  $texte= mysqli_real_escape_string( $dbsource, $Resume_source ) ;
			  $texte = "\"$texte\"";
			
			  $RequeteSQLUPDATE="UPDATE movie SET resume=$texte where id_film=$id_film ;"; 
			  $ResultUPDATE=mysqli_query($dbcible,$RequeteSQLUPDATE);      
			  //echo $RequeteSQLUPDATE;
			  echo "</br>";
			  if (!$ResultUPDATE) { die('Invalid query: ' . mysqli_error($dbcible)); }
			}
			
			
			
			}
			?>
			</div>
        </div>
		


		</div>
	</body>

</html>