<?php
require_once("./Fonc_Base.php");
require_once("./Fonc_gestion_BASE.php");
$db= ouvrir_base ();

// RECUP DES PLACES LIBRES //
$places_VHS=trouver_liste_place_libre(1);
$places_DVD=trouver_liste_place_libre(2);
$places_CD=trouver_liste_place_libre(3);
$places_Book=trouver_liste_place_libre(4);

//Old version
$liste_support_VHS=liste_support_VHS();
$liste_support_DVD=liste_support_DVD();
$liste_support_CD=liste_support_CD();
$liste_support_Book=liste_support_Book();

$liste_des_pays = liste_pays();
$liste_des_genres = liste_genre() ;

$id=$_REQUEST['id'];

// RECUP des infos MOVIE
$RequeteSQLfilm="select distinct * from movie m where m.id_film=$id";
$RequeteSQL=$RequeteSQLfilm ;
$Result=mysql_query($RequeteSQL);
if (!$Result) { die('Invalid query: ' . mysql_error()); }

	$Fiche = mysql_fetch_array($Result, MYSQL_ASSOC);
	$Titre=$Fiche['titre'];
	$Realisateur=$Fiche['realisateur'];
	$Annee=$Fiche['annee'];
	$Duree=$Fiche['duree'];
	$Title=$Fiche['title'];
	$Resume=$Fiche['resume'];
	$Image=$Fiche['image'];
	
// RECUP des infos PAYS
	$RequeteSQLpays="select distinct * from pays p, est_produit_par_un_pays ep where p.id_pays=ep.id_pays ";
	$RequeteSQL=$RequeteSQLpays ;
	$RequeteSQL.=" and id_film=$id";
   $Result=mysql_query($RequeteSQL);
   if (!$Result) { die('Invalid query: ' . mysql_error()); }

	$nb_pays=0;
	$Chaine_pays="";
	while ( $Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ) 
	{  
		$nb_pays++ ; $Pays=$Fiche['pays']; $id_pays=$Fiche['id_pays'];
		$Chaine_pays.="<option value=\"$id_pays\" selected >$Pays</option>"; 
	} 

// RECUP des infos Genre
	$RequeteSQLgenre="select distinct * from est_du_genre eg, genre g where g.id_genre=eg.id_genre " ;
	$RequeteSQL=$RequeteSQLgenre;
	$RequeteSQL.=" and id_film=$id ";
	$Result=mysql_query($RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysql_error()); }

	$nb_genre=0;
	$Chaine_genre="";
	while ( $Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ) 
	{  
		$nb_genre++ ; $Genre=$Fiche['genre']; $id_genre=$Fiche['id_genre'];
		$Chaine_genre.="<option value=\"$id_genre\" selected >$Genre</option>"; 
	} 

// RECUP des infos Emplacement
	$RequeteSQLnumero="select distinct * from emplacement e, est_enregistre er, type_support t, videotheque v where t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement and e.id_videotheque=v.id_videotheque " ;
	$RequeteSQL=$RequeteSQLnumero;
	$RequeteSQL.=" and id_film=$id";
	$Result=mysql_query($RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysql_error()); }

	$nb_VHS=0;
	$nb_DVD=0;
	$nb_CD=0;
	$nb_Book=0;
	$le_proprio_VHS="";	$proprio_VHS="";	$option_VHS=""; $Numero_VHS=""; $id_type_VHS=""; 	$Type_VHS=""; 	$VHS="";	$VHS_state="disabled";
	$le_proprio_DVD="";	$proprio_DVD="";	$option_DVD="";	$Numero_DVD=""; $id_type_DVD=""; 	$Type_DVD=""; 	$DVD="";	$DVD_state="disabled";
	$le_proprio_CD="";	$proprio_CD="";		$option_CD="";	$Numero_CD=""; 	$id_type_CD=""; 	$Type_CD=""; 	$CD="";		$CD_state="disabled";
	$le_proprio_Book="";$proprio_Book="";	$option_Book="";$Numero_Book="";$id_type_Book=""; 	$Type_Book=""; 	$Book="";	$Book_state="disabled";

	while ( $Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ) 
	{
		$videotheque=$Fiche['id_videotheque'];
		echo $videotheque;
	
		if ($videotheque==1) 
		{
			$nb_VHS++; $VHS_state="";
			$VHS = "checked" ; $Numero_VHS=$Fiche['numero_de_classement']; $id_type_VHS=$Fiche['id_type_support'] ; 
			$Type_VHS=$Fiche['capacite_support'] ; $Proprio_VHS=$Fiche['proprio_video'] ; $id_emplacement_VHS=$Fiche['id_emplacement'];
			$option_VHS="<option value=\"$id_emplacement_VHS\" selected >$Numero_VHS</option>"; 
			$le_proprio_VHS="<option value=\"$Proprio_VHS\" selected >$Proprio_VHS</option>"; 
			$type_de_VHS="<option value=\"$id_type_VHS\" selected >$Type_VHS</option>";
		}

		if ($videotheque==2) 
		{
			$nb_DVD++;	$DVD_state="";
			$DVD = "checked" ; $Numero_DVD=$Fiche['numero_de_classement']; $id_type_DVD=$Fiche['id_type_support'] ; 
			$Type_DVD=$Fiche['capacite_support'] ; $Proprio_DVD=$Fiche['proprio_video']; $id_emplacement_DVD=$Fiche['id_emplacement'];
			$option_DVD="<option value=\"$id_emplacement_DVD\" selected >$Numero_DVD</option>";	
			$le_proprio_DVD="<option value=\"$Proprio_DVD\" selected >$Proprio_DVD</option>"; 
			$type_de_DVD="<option value=\"$id_type_DVD\" selected >$Type_DVD</option>"; 
		}

		if ($videotheque==3) 
		{
			$nb_CD++; $CD_state="";
			$CD = "checked" ; $Numero_CD=$Fiche['numero_de_classement']; $id_type_CD=$Fiche['id_type_support'] ; 
			$Type_CD=$Fiche['capacite_support'] ; ; $Proprio_CD=$Fiche['proprio_video'];$id_emplacement_CD=$Fiche['id_emplacement'];
			$option_CD="<option value=\"$id_emplacement_CD\" selected>$Numero_CD</option>";	
			$le_proprio_CD="<option value=\"$Proprio_CD\" selected >$Proprio_CD</option>"; 
			$type_de_CD="<option value=\"$id_type_CD\" selected >$Type_CD</option>"; 
		}

		if ($videotheque==4) 
		{
			$nb_Book++;	$Book_state="";
			$Book = "checked" ; $Numero_Book=$Fiche['numero_de_classement']; $id_type_Book=$Fiche['id_type_support'] ; 
			$Type_Book=$Fiche['capacite_support'] ; ; $Proprio_Book=$Fiche['proprio_video'];$id_emplacement_Book=$Fiche['id_emplacement'];
			$option_Book="<option value=\"$id_emplacement_Book\" selected>$Numero_Book</option>";	
			$le_proprio_Book="<option value=\"$Proprio_Book\" selected >$Proprio_Book</option>"; 
			$type_de_Book="<option value=\"$id_type_Book\" selected >$Type_Book</option>"; 
		}
	}
	if ($nb_VHS>1 or $nb_DVD>1 or $nb_CD>1 or $nb_Book>1) {echo "<br><b>ALERT : n_VHS=$nb_VHS / n_DVD=$nb_DVD / n_CD=$nb_CD / n_Book=$nb_Book </b><br>" ;}



fermer_base ($db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta name="generator" content="Adobe GoLive 6">
		<title>Movie Modif</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
		<link href="main.css" rel="stylesheet" media="screen">
		<script type="text/javascript"><!--
function ValidClick()
{
return confirm("Etes-vous sur de vouloir supprimer ?");
}
function selectVHS()
{
	document.forms['Ajout'].Numero_VHS.disabled=false;
	document.forms['Ajout'].Numero_libre_VHS.disabled=false;
	document.forms['Ajout'].Proprio_VHS.disabled=false;
	document.forms['Ajout'].Type_VHS.disabled=false;

	var nb=parseInt(<?php echo $places_VHS[0] ?>);
	var txt =<?php 
	$ST= " new Array ('$places_VHS[1]'";
	for ($k = 2; $k <= $places_VHS[0]; $k++) 
	{
		$ST .= ", '$places_VHS[$k]'";
	}
	$ST .=");";
	echo $ST;
	?>;

	document.forms['Ajout'].Numero_VHS.options.length = nb;
	for (i=0;i<nb;i++) 
	{ 
		document.forms['Ajout'].Numero_VHS.options[i].text=txt[i] ; 
		document.forms['Ajout'].Numero_VHS.options[i].value=txt[i] ; 
	}

}
function selectDVD()
{
	document.forms['Ajout'].Numero_DVD.disabled=false;
	document.forms['Ajout'].Numero_libre_DVD.disabled=false;
	document.forms['Ajout'].Proprio_DVD.disabled=false;
	document.forms['Ajout'].Type_DVD.disabled=false;

	var nb=parseInt(<?php echo $places_DVD[0] ?>);
	var txt =<?php 
				$ST= " new Array ('$places_DVD[1]'";
				for ($k = 2; $k <= $places_DVD[0]; $k++) 
				{
					$ST .= ", '$places_DVD[$k]'";
				}
				$ST .=");";
				echo $ST;
			 ?>;

	document.forms['Ajout'].Numero_DVD.options.length = nb;
	for (i=0;i<nb;i++) 
	{ 
		document.forms['Ajout'].Numero_DVD.options[i].text=txt[i] ; 
		document.forms['Ajout'].Numero_DVD.options[i].value=txt[i] ; 
	}
}

function selectCD()
{
	document.forms['Ajout'].Numero_CD.disabled=false;
	document.forms['Ajout'].Numero_libre_CD.disabled=false;
	document.forms['Ajout'].Proprio_CD.disabled=false;
	document.forms['Ajout'].Type_CD.disabled=false;

	var nb=parseInt(<?php echo $places_CD[0] ?>);
	var txt = <?php 
				$ST= " new Array ('$places_CD[1]'";
				for ($k = 2; $k <= $places_CD[0]; $k++) 
				{
				$ST .= ", '$places_CD[$k]'";
				}
				$ST .=");";
				echo $ST;
			  ?>;

	document.forms['Ajout'].Numero_CD.options.length = nb;
	for (i=0;i<nb;i++) 
	{ 
		document.forms['Ajout'].Numero_CD.options[i].text=txt[i] ; 
		document.forms['Ajout'].Numero_CD.options[i].value=txt[i] ; 
	}
return
}

function selectBook()
{
	document.forms['Ajout'].Numero_Book.disabled=false;
	document.forms['Ajout'].Numero_libre_Book.disabled=false;
	document.forms['Ajout'].Proprio_Book.disabled=false;
	document.forms['Ajout'].Type_Book.disabled=false;

	var nb=parseInt(<?php echo $places_Book[0] ?>);
	var txt =<?php 
				$ST= " new Array ('$places_Book[1]'";
				for ($k = 2; $k <= $places_Book[0]; $k++) 
				{
					$ST .= ", '$places_Book[$k]'";
				}
				$ST .=");";
				echo $ST;
			?>;
				document.forms['Ajout'].Numero_Book.options.length = nb;
				for (i=0;i<nb;i++) 
				{ 
					document.forms['Ajout'].Numero_Book.options[i].text=txt[i] ; 
					document.forms['Ajout'].Numero_Book.options[i].value=txt[i] ; 
				}
	return
}

function selectVHS_OFF()
{
	document.forms['Ajout'].Numero_VHS.options[0].text="x";
	document.forms['Ajout'].Numero_VHS.options[0].value='x';
	document.forms['Ajout'].Numero_VHS.selectedIndex=0;

	document.forms['Ajout'].Numero_VHS.disabled=true;
	document.forms['Ajout'].Numero_libre_VHS.disabled=true;
	document.forms['Ajout'].Proprio_VHS.disabled=true;
	document.forms['Ajout'].Type_VHS.disabled=true;

}
function selectDVD_OFF()
{
	document.forms['Ajout'].Numero_DVD.options[0].text="x";
	document.forms['Ajout'].Numero_DVD.options[0].value='x';
	document.forms['Ajout'].Numero_DVD.selectedIndex=0;

	document.forms['Ajout'].Numero_DVD.disabled=true;
	document.forms['Ajout'].Numero_libre_DVD.disabled=true;
	document.forms['Ajout'].Proprio_DVD.disabled=true;
	document.forms['Ajout'].Type_DVD.disabled=true;
}

function selectCD_OFF()
{
	document.forms['Ajout'].Numero_CD.options[0].text="x";
	document.forms['Ajout'].Numero_CD.options[0].value='x';
	document.forms['Ajout'].Numero_CD.selectedIndex=0;
	document.forms['Ajout'].Numero_CD.disabled=true;
	document.forms['Ajout'].Numero_libre_CD.disabled=true;
	document.forms['Ajout'].Proprio_CD.disabled=true;
	document.forms['Ajout'].Type_CD.disabled=true;
}
function selectBook_OFF()
{
	document.forms['Ajout'].Numero_Book.options[0].text="x";
	document.forms['Ajout'].Numero_Book.options[0].value='x';
	document.forms['Ajout'].Numero_Book.selectedIndex=0;
	document.forms['Ajout'].Numero_Book.disabled=true;
	document.forms['Ajout'].Numero_Book.value="";
	document.forms['Ajout'].Numero_libre_Book.disabled=true;
	document.forms['Ajout'].Proprio_Book.disabled=true;
	document.forms['Ajout'].Type_Book.disabled=true;
}
// -->
</script>
	</head>

	<body bgcolor="#ffffff">
		<h1>Movieth&egrave;que : <font color="blue">Modification fiche</font></h1>
		<hr>
		<table width="596" border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td valign="middle">
					<h3><img src="Web_image/Crayon.jpg" alt="" height="23" width="20" border="0">= modifier</h3>
				</td>
				<td valign="bottom"></td>
				<td valign="middle">
					<h3><img src="Web_image/trash.gif" alt="" height="20" width="21" border="0">= supprimer</h3>
				</td>
				<td valign="middle">
					<div align="center">
						<a href="/Movietheque/MovieAjout.php">Ajouter une fiche</a></div>
			  </td>
				<td valign="bottom" width="20"></td>
				<td valign="middle">
					<div align="center">
						<a href="index.html">Retour accueil</a></div>
				</td>
			</tr>
		</table>
		<div align="center">
			<hr>
		    <form action="MovieListe.php" method="post" name="Ajout" enctype="multipart/form-data">
              <div align="left">
                <p>
                 <input type="hidden" name="Origine" value="Modif">
                 <input name="id" type="hidden" id="id" value="<?php echo $id ; ?>">
                </p>
                <table width="962" border="0">
					<tr>
						<td width="469">
							<table width="469" border="0" cellspacing="4" cellpadding="0" bgcolor="#CCFFFF"> <!-- Partie Gauche Formulaire-->
								<tr>
									<td width="152"><p>Titre du film</p></td>
									<td><p><input name="Titre" type="text" value="<?php echo $Titre; ?>" size="47"></p></td>
								</tr>
								<tr>
									<td width="152"><p>R&eacute;alisateur</p></td>
									<td><p><input name="Realisateur" type="text" value="<?php echo $Realisateur; ?>" size="47"></p></td>
								</tr>
								<tr>
									<td width="152"><p>Ann&eacute;e</p></td>
									<td><input name="Annee" type="text" value="<?php echo $Annee; ?>" size="4"></td>
								</tr>
								<tr>
									<td width="152"><p>Pays</p></td>
									<td><p><select name="Pays[]" size="3" multiple><?php echo $Chaine_pays; echo $liste_des_pays; ?></select></p></td>
								</tr>
								<tr>
									<td width="152"><p>Genre</p></td>
									<td><p><select name="Genre[]" multiple size="3"><?php echo $Chaine_genre; echo $liste_des_genres ;?></select></p></td>
								</tr>
								<tr>
									<td width="152"><p>Dur&eacute;e (en minutes)</p></td>
									<td><p><input name="Duree" type="text" value="<?php echo $Duree; ?>" size="4"></p></td>
								</tr>
								<tr>
									<td valign="top" width="152"><p>R&eacute;sum&eacute; du film</p></td>
									<td><textarea name="Resume" rows="8" cols="46"><?php echo $Resume; ?></textarea></td>
								</tr>
								<tr>
									<td colspan="2"><p> </p></td>
								</tr>
								<tr>
									<td width="152"><p>Titre original</p></td>
									<td><p><input name="Title" type="text" value="<?php echo $Title; ?>" size="41"></p></td>
								</tr>
								<tr>
									<td><p>Affiche du film</p></td>
									<td><input name="Image" type="file" size="36"></td>
								</tr>

							</table>
							<!-- fin table de gauche pour info film -->
							
						</td>

						<td width="483" valign="bottom"><!-- 2eme colonne = 2eme Table pour le rangement -->
							<table width="476" height="23" border="0" cellspacing="3"><!-- 2eme colonne = 2eme Table pour le rangement -->
								<tr> <!-- Titre classement -->
									<td width="118"><p><strong>Classement Videotheque</strong></p></td>
									<td width="103">&nbsp;</td>
									<td width="103">&nbsp;</td>
									<td width="129">&nbsp;</td>
								</tr><!-- Fin titre classement-->
								<tr><!-- ZONE type de rangement-->  
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="VHS" type="checkbox" id="VHS" onChange="if(!document.forms['Ajout'].VHS.checked) {selectVHS_OFF();} else  {selectVHS();}" value="1" <?php echo $VHS; ?>>
										VHS-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="DVD" type="checkbox" id="DVD" onChange="if(!document.forms['Ajout'].DVD.checked) {selectVHS_DVD();} else  {selectDVD();}" value="1" <?php echo $DVD; ?>>
										DVD-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="CD" type="checkbox" id="CD" onChange="if(!document.forms['Ajout'].CD.checked) {selectCD_OFF();} else  {selectCD();}" value="1" <?php echo $CD; ?>>
										CD-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="Book" type="checkbox" id="Book" onChange="if(!document.forms['Ajout'].Book.checked) {selectBook_OFF();} else  {selectBook();}" value="1" <?php echo $Book; ?>>
										DVD-CD-Book</strong></p>
									</td>  	      
								</tr>
								<tr><!-- new no enregistrement -->
									<td><p align="center">N&deg; d'enregistrement<br>
										<select name="Numero_VHS"  size="1" <?php echo $VHS_state;?> id="Numero_VHS"><<?php echo $places_VHS[$places_VHS[0]+1]; ?> <?php echo $option_VHS;?></select><br>
										<label>Eventuellement <br> saisie libre <br>
											<input name="Numero_libre_VHS" type="text" <?php echo $VHS_state;?>  id="Numero_libre_VHS" value="<?php echo $Numero_VHS;?>" size="5" maxlength="4">
										</label></p>                              
									</td>
						
									<td><p align="center">N&deg; d'enregistrement<br>
										<select name="Numero_DVD" size="1" <?php echo $DVD_state;?> id="Numero_DVD"><?php echo $option_DVD;?> <?php echo $places_DVD[$places_DVD[0]+1]; ?></select><br>
										<label>Eventuellement <br> saisie libre <br>
											<input name="Numero_libre_DVD" type="text" <?php echo $DVD_state;?>  id="Numero_libre_DVD" value="<?php echo $Numero_DVD;?>" size="5" maxlength="4">
										</label></p>
									</td>
									
									<td><p align="center">N&deg; d'enregistrement<br>
										<select name="Numero_CD" size="1" <?php echo $CD_state;?> id="Numero_CD"><?php echo $option_CD;?> <?php echo $places_CD[$places_CD[0]+1]; ?></select>
										<br>
										<label>Eventuellement <br> saisie libre <br>
											<input name="Numero_libre_CD" type="text" <?php echo $CD_state;?> id="Numero_libre_CD" value="<?php echo $Numero_CD;?>" size="5" maxlength="4">
										</label></p>
									</td>
									
									<td><p align="center">N&deg; d'enregistrement<br>
										<select name="Numero_Book" size="1" <?php echo $Book_state;?> id="Numero_Book"><?php echo $option_Book;?> <?php echo $places_Book[$places_Book[0]+1]; ?></select>
										<br>
										<label>Eventuellement <br>saisie libre <br>
											<input name="Numero_libre_Book" type="text" <?php echo $Book_state;?> id="Numero_libre_Book" value="<?php echo $Numero_Book;?>" size="5" maxlength="4">
										</label></p>                              
									</td>
								</tr>
								
								<!--fin zone no -->
					
								<tr><!-- Zone proprio de la vidéo -->
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_VHS" size="1" <?php echo $VHS_state;?> id="Proprio_VHS" >
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
											<?php echo "$le_proprio_VHS"; ?>
										</select></p>                              	
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_DVD" size="1" <?php echo $DVD_state;?> id="Proprio_DVD">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
											<?php echo "$le_proprio_DVD"; ?>
										</select></p>
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_CD" size="1" <?php echo $CD_state;?> id="Proprio_CD">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
											<?php echo "$le_proprio_CD"; ?>
										</select></p>
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_Book" size="1" <?php echo $Book_state;?>  id="Proprio_Book">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
										<?php echo "$le_proprio_Book"; ?>
										</select></p>
									</td>
								</tr>
								<!-- Fin zone appartenance ... -->
								<tr><!-- Zone type d'enregistrement E120, DVD, ... -->
									<td><p align="center">mod&egrave;le<br>
										<select name="Type_VHS" size="1" <?php echo $VHS_state;?>  id="Type_VHS">
											<?php echo $liste_support_VHS; echo $type_de_VHS;?>
										</select></p>
									</td>
									<td><p align="center">mod&egrave;le<br>
										<select name="Type_DVD" size="1" <?php echo $DVD_state;?>  id="Type_DVD">
											<?php echo $liste_support_DVD; echo $type_de_DVD;?>
										</select></p>
									</td>
									<td><div align="center">
										<p>mod&egrave;le<br>
										<select name="Type_CD" size="1" <?php echo $CD_state;?>  id="Type_CD">
											<?php echo $liste_support_CD; echo $type_de_CD;?>
										</select></p>
										</div>
									</td>
									<td><div align="center">
										<p>mod&egrave;le<br>
										<select name="Type_Book" size="1" <?php echo $Book_state;?>  id="Type_Book">
											<?php echo $liste_support_Book; echo $type_de_Book;?>
										</select></p>
										</div>
									</td>
								</tr>
								<!-- FIN Zone type d'enregistrement E120, DVD, ... -->  
								<tr> <!-- Zone Boutons -->
									<td><input type="reset"></td>
									<td>&nbsp;</td>
									<td><input type="submit" name="submitButtonName"></td>
									<td>&nbsp;</td>
								</tr>
							</table>
							

							<!-- Fin 2eme Table de la 2eme colonne-->
							
						</td>
					</tr>

                </table>
              </div>
	      </form>
		</div>
	</body>

</html>