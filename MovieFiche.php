<?php 
require_once("./Fonc_Base.php");
include("./RedimPict.php");
$db= ouvrir_base ();
$id= $_REQUEST['id'];

$table="movie";
 // ### Recup des infos film #####
 $RequeteSQL="select * FROM $table where id_film=$id"; 
 $Result=mysqli_query($db,$RequeteSQL);      
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
$Fiche = mysqli_fetch_assoc($Result) ;
	$Titre=$Fiche['titre'];
	$Realisateur=$Fiche['realisateur'];
	$Annee=$Fiche['annee'];	 
	$Duree=$Fiche['duree'];
	$Title=$Fiche['title'];
	$Resume=$Fiche['resume'];
	$Image=$Fiche['image'];				
	$LeResu=nl2br($Resume);
mysqli_free_result($Result);

 // ### Recup des infos PAYS #####
 $RequeteSQL="select distinct * from pays p, est_produit_par_un_pays ep where p.id_pays=ep.id_pays AND id_film=$id"; 
 $Result=mysqli_query($db,$RequeteSQL);      
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
$Pays="";
$nb_pays=0;
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
	{  $nb_pays++ ; if ($nb_pays>1) {$Pays.="/";}  $Pays.= $Fiche['pays']; } 
mysqli_free_result($Result);

// RECUP des infos Genre
  $RequeteSQL="select distinct * from est_du_genre eg, genre g where g.id_genre=eg.id_genre and id_film=$id ";
  $Result=mysqli_query($db,$RequeteSQL);
  if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
$nb_genre=0;
$Genre="";
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
{  $nb_genre++ ; if ($nb_genre>1) {$Genre.="/";}  $Genre.= $Fiche['genre']; }
 mysqli_free_result($Result);

//==========================================================
// RECUP des infos Emplacement
$RequeteSQLnumero="select distinct * from emplacement e, est_enregistre er, type_support t where t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement" ;
$RequeteSQL=$RequeteSQLnumero;
$RequeteSQL.=" and id_film=$id";
   $Result=mysqli_query($db,$RequeteSQL);
   if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }

$nb_VHS=0;
$nb_DVD=0;
$nb_CD=0;
$nb_DVDBook=0;
$proprio_VHS=""; $Numero_VHS="";  $Type_VHS=""; $VHS="";
$proprio_DVD=""; $Numero_DVD="";  $Type_DVD=""; $DVD="";
$proprio_CD=""; $Numero_CD=""; $Type_CD=""; $CD="";
$proprio_DVDBook=""; $Numero_DVDBook=""; $Type_DVDBook=""; $DVDBook="";
$Support="";
	 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
{
	$videotheque=$Fiche['id_videotheque'];
	
	if ($videotheque==1) {
	$nb_VHS++;	$Numero_VHS=$Fiche['numero_de_classement'] ;
	$Type_VHS=$Fiche['capacite_support'] ; $Proprio_VHS=$Fiche['proprio_video'] ; 
	$Support.="</br>&nbsp;-&nbsp;VHS $Type_VHS (n°$Numero_VHS)" ;}

	if ($videotheque==2) {
	$nb_DVD++; $Numero_DVD=$Fiche['numero_de_classement']; 
	$Type_DVD=$Fiche['capacite_support'] ; $Proprio_DVD=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;-&nbsp;DVD $Type_DVD (n°$Numero_DVD)";}

	if ($videotheque==3) {
	$nb_CD++; $Numero_CD=$Fiche['numero_de_classement']; 
	$Type_CD=$Fiche['capacite_support'] ;  $Proprio_CD=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;-&nbsp;CD $Type_CD (n°$Numero_CD)";}
	
	if ($videotheque==4) {
	$nb_DVDBook++; $Numero_DVDBook=$Fiche['numero_de_classement']; 
	$Type_DVDBook=$Fiche['capacite_support'] ;  $Proprio_DVDBook=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;-&nbsp;DVDBook $Type_DVDBook (n°$Numero_DVDBook)";}

}
mysqli_free_result($Result);

 // preaa des id suivants et precedents
$Res=mysqli_query($db,"select id_film from $table where id_film >=$id Limit 2 "); 
 if (!$Res) { die('Invalid query: ' . mysqli_error($db)); }
$R=mysqli_fetch_assoc($Res) ;
$R=mysqli_fetch_assoc($Res) ;
$IdNext=$R['id_film'];
mysqli_free_result($Res);

$Res=mysqli_query($db,"select id_film  from $table where id_film <=$id order by id_film desc Limit 2 "); 
 if (!$Res) { die('Invalid query: ' . mysqli_error($db)); }
$R=mysqli_fetch_assoc($Res) ;
//if ($R['c']>1) 
$R=mysqli_fetch_assoc($Res) ;
//echo $R['c']; echo "PREV";
$IdPrev=$R['id_film'];
mysqli_free_result($Res);

fermer_base ($db);

$Mypic=0;
if (strlen($Image)!=0 ) 
{
$Mypic=1;
$path="./Illustrations/";
$movie=$Image;
$x=Redim_Pict($path, $movie, 200,300);
$Largeur= $x['Largeur'];
$Hauteur=$x['Hauteur'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta name="generator" content="Adobe GoLive 6">
		<title>Cartoonoth&egrave;que</title>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<link href="main.css" rel="stylesheet" media="screen">
		<script type="text/javascript"><!--
function ValidClick()
{
return confirm("Etes-vous sur de vouloir supprimer ?");
}
// -->
</script>
	</head>

	<body bgcolor="#ffffff">
		<h1>Vid&eacute;oth&egrave;que : <font color="blue"> fiche</font></h1>
		<hr>
		<table width="742" border="0" cellspacing="5" cellpadding="0">
			<tr>
				<td valign="middle" width="175">
					<div align="center">
						<p><a href="MovieListe.php?Origine=Accueil#<?php echo $id;?> ">Liste des films</a></p>
					</div>
				</td>
				<td valign="bottom" width="20"></td>
				<td valign="middle" width="200">
					<div align="center">
						<a href="index.html">Retour accueil</a></div>
				</td>
				<td valign="middle" width="30"></td>
				<td valign="middle" width="30"><p><a href="MovieModif.php?id=<?php echo $id; ?>"><img src="Web_image/Crayon.jpg" alt="" height="23" width="20" border="0"></a></p></td>
				<td valign="middle" width="30"></td>
				<td valign="middle" width="30"><a onClick="return ValidClick()" href="MovieListe.php?Origine=Suppr&id=<?php echo $id;?>"><img src="Web_image/trash.gif" alt="" height="20" width="21" border="0"></a></td>
			</tr>
		</table>
		<div align="center">
			<hr>
		</div>
		<div align="left">
			<table width="483" border="0" cellspacing="5" cellpadding="0">
				<tr>
					<td valign="top" width="180"></td>
					<td>
						<table width="128" border="0" cellspacing="2" cellpadding="0">
							<tr height="21">
								<td height="21"><a href="MovieFiche.php?id=<?php echo $IdPrev ;?>"><img src="Web_image/FlecheG.gif" alt="" height="19" width="20" border="0"></a></td>
								<td height="21"></td>
								<td height="21"><a href="MovieFiche.php?id=<?php echo $IdNext;?>"><img src="Web_image/FlecheD.gif" alt="" height="19" width="20" border="0"></a></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td rowspan="10" valign="top" width="180">
                    <noedit>
					<?php if($Mypic==1) {echo "<a href=\"./Illustrations/$Image\" ><img src=\"./Illustrations/$Image\" height= \"$Hauteur\" width=\"$Largeur\"  border=\"0\"></a>";} ?></noedit>
                    </td>
					<td>
						<h3><noedit><?php echo $Titre;?></noedit></h3>
					</td>
				</tr>
				<tr>
					<td>
						<div align="left">
							<i><font id="auteur" color="#d8bfd8">[<noedit><?php echo $Title;?></noedit>]</font></i></div>
					</td>
				</tr>
				<tr>
				<td><noedit><font id="auteur" color="#d8bfd8">Realisateur : <?php echo $Realisateur;?></font></noedit></td>
			</tr>
				<tr>
				<td><noedit><font id="auteur" color="#d8bfd8">Origine : <?php echo $Pays;?>
                <br>Genre : <?php echo $Genre;?>
                <br>Annee : <?php echo $Annee;?>
                </font></noedit></td>
			</tr>
				<tr>
				<td><noedit><p><?php echo $LeResu;?></p></noedit></td>
			</tr>
				<tr>
				<td><noedit><font id="auteur" color="#d8bfd8"><br/>Dur&eacute;e :<?php echo "$Duree mn"; 
																	echo "<br/>";
																	echo "Support : $Support";
																	echo "<br/>"; ?></font></noedit></td>
			</tr>
				<tr>
				<td></td>
			</tr>
				<tr>
				<td></td>
			</tr>
				<tr>
				<td></td>
			</tr>
				<tr>
				<td></td>
			</tr>
			</table>
		</div>
		<div align="left">
			<p></p>
		</div>
	</body>

</html>