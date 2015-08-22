<?php 

// 
//require_once("./Fonc_BASE.php");
//include("./RedimPict.php");
//$db= ouvrir_base ();

//$id= $_REQUEST['id'];

	$host="localhost"; //Nom de l'hôte mysql     
	$user="root"; //Nom d'utilisateur de la base de données
	$pw=""; //Mot de passe de votre base
    
	$database="videobs"; //Nom de otre base de données
	$database="video"; //Nom de botre base de données
	//$database="mon_sql"; //Nom de botre base de données
	
	$db=mysqli_connect($host,$user,$pw, $database);
	
	$id1=$_REQUEST['id1'];

	$id2=$_REQUEST['id2'];
	
	$update=$_REQUEST['update'];
		//echo $id2;
	
$table="movie";
 // ### Recup des infos film #####
 $RequeteSQL="select * FROM $table where (id_film >= $id1) and (id_film <= $id2)"; 
  // $RequeteSQL="select * FROM $table "; 

 $Result=mysqli_query($db,$RequeteSQL);      
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); } else {$Compte= mysqli_num_rows($Result) ;}
//$Fiche = mysqli_fetch_assoc($Result) ;

//	$Titre=$Fiche['titre'];
//	$Resume=$Fiche['resume'];
//	$LeResu=nl2br($Resume);
//mysqli_free_result($Result);


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
			while ( $Fiche = mysqli_fetch_assoc($Result) ) 
			{
			$Titre=$Fiche['titre'];
			$Resume=$Fiche['resume'];
			$numero=$Fiche['id_film'];
			echo "$numero   $Titre";
			echo "</br>";
			echo "<h6>"; 
			//echo utf8_decode($Resume) ;
			echo ($Resume) ;
			echo "</h6>";
			
			if ($update=="yes") 
			{ $texte= mysqli_real_escape_string( $db, utf8_decode($Resume) ) ;
			  $texte = "\"$texte\"";
			
			  $RequeteSQLUPDATE="UPDATE movie SET resume=$texte where id_film=$numero ;"; 
			  $ResultUPDATE=mysqli_query($db,$RequeteSQLUPDATE);      
			  //echo $RequeteSQLUPDATE;
			  echo "</br>";
			  if (!$ResultUPDATE) { die('Invalid query: ' . mysqli_error($db)); }
			}
			
			
			
			}
			?>
			</div>
        </div>
		


		</div>
	</body>

</html>