<?php

//  Acces base de données
require_once("./Fonc_BASE.php");  // source pour acces base de données
$db= ouvrir_base (); // connexion base de données


// paramètres nécessaires pour fich image (ajout ou modif)
// Info pour fichimage.php
//  $table="movie";
//  $entete="Film"; 


///////////////////////////////////////////////////////
//  PREPARATION et REQUETES pour selection
//########### SELECTION ###############################
if ( $_REQUEST['Origine']=="Selection") 
{
		// Recup des arguments du POST/GET 
	$Titre=STRTOUPPER( $_REQUEST['Titre'] );
	$Realisateur=$_REQUEST['Realisateur'];
	$Annee=$_REQUEST['Annee'];
 
 	$Genre=$_REQUEST['Genre'];
	$Duree=$_REQUEST['Duree'];
	$Title=STRTOUPPER( $_REQUEST['Title'] );
	$Support=$_REQUEST['Support'];
	
		// Construction de la requete MYSQL selon les arguments renseignés
	$Selec="";
	$Suite=0;
	if ( $Titre != "" ) {$Selec .=" and UPPER(titre) like '%$Titre%' ";  $Suite=1;}
	if ( $Title != "" ) { $Selec .="and UPPER(title) like '%$Title%' ";  $Suite=1;}
	if ( $Realisateur != "" ) { $Selec .="and realisateur like '%$Realisateur%' "; $Suite=1;} 
	if ( $Annee != "" ) { $Selec .="and annee='$Annee' ";  $Suite=1;}

	if ( $Genre != "" ) { $Selec .="and eg.id_genre = '$Genre' ";  $Suite=1;}
	if ( $Duree != "" ) { $Selec .="and duree < $Duree ";  $Suite=1;}
	if ( $Support != "" ) { $Selec .="and e.id_videotheque = '$Support' ";$Suite=1;}

$RequeteSQL="select distinct * from movie m, emplacement e, est_enregistre er, est_du_genre eg, genre g, type_support t, videotheque v where  m.id_film=er.id_film and m.id_film=eg.id_film and g.id_genre=eg.id_genre and t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement and e.id_videotheque=v.id_videotheque ";

	if (isset ($_REQUEST['Ordre']) ) 
	{ $Ord = $_REQUEST['Ordre'] ;}
	else
	{ $Ord="m.titre"; 
		$Ord="e.numero_de_classement";}
	
	
	if ($Suite==0) 
	{	$RequeteSQL.="order by $Ord";}
	else
	{	$RequeteSQL.=" $Selec order by $Ord";}



	$Result=mysqli_query($db,$RequeteSQL);  // requete sur base de donnees
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	else 
	{
		$Compte= mysqli_num_rows($Result) ;  // recup des resultats quand il y en a 
	}
 
}





?>


<!DOCTYPE html>
<html lang="fr">

	<?php
		include('entete.html');
	?>

	<body bgcolor="#ffffff">

		<?php
		include('navigationConsult.html');
		?>
		<div class="container-fluid">
		<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped" font-size="9">
				<thead>
					<tr>
					<th>id</th><th>Biblio</th><th>N&deg;</th><th>Titre</th><th>Genre</th><th>Dur&eacute;e</th><th>R&eacute;alisateur</th><th>Ann&eacute;e</th>
					<th>Proprio</th><th>Support</th><th>Mod&egrave;le </th><th>Titre original</th><th>R&eacute;sum&eacute;</th><th>Image</th>
					</tr>
				</thead>
				<tbody style="font-size:8px;">
				
					<!-- RECUP DES DONNEES ISSUES DE LA REQUETE MYSQL -->
					<?php
					while ( $Fiche = mysqli_fetch_assoc($Result) ) 
					{ 
					// Recup des données
						$id=$Fiche['id_film'];
						$Titre=($Fiche['titre']);
						$Realisateur=($Fiche['realisateur']);
						$Annee=$Fiche['annee'];
						$Duree=$Fiche['duree'];
						$Title=($Fiche['title']);
						$Resume=($Fiche['resume']);
						$LeResu=nl2br($Resume);
						$Image=$Fiche['image'];
						$Genre=($Fiche['genre']);
						$Support=$Fiche['type_de_support'];
						$Numero=$Fiche['numero_de_classement'];
						$Proprio=$Fiche['proprio_video'];
						$Capa=$Fiche['capacite_support'];
						$Videotheque=$Fiche['id_videotheque'];
					// remplissage HTML
						echo "<tr><td>$id</td>";
						echo "<td >$Videotheque</td>";
						echo "<td>$Numero</td>";
						echo "<td><a href=\"./MovieFiche.php?id=$id\">$Titre</a></td>";
						echo "<td>$Genre</td>";
						echo "<td>$Duree mn</td>";
						echo "<td>$Realisateur</td>";
						echo "<td>$Annee</td>";
						echo "<td>$Proprio</td>";
						echo "<td>$Support</td>";
						echo "<td>$Capa</td>";
						echo "<td>$Title</td>";
						if ( $Resume != "") { $Texte="xxx";} else {$Texte="";}
						echo "<td>$Texte</td>";
						echo "<td>$Image</td>";
						echo "</tr>\n";
 					}
					mysqli_free_result($Result);
 					fermer_base ($db);
					?>
					
				</tbody>
			</table>
		</div>
		</div>
		</div>
        
		
	</body>
</html>