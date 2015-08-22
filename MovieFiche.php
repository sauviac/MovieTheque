<?php 
require_once("./Fonc_BASE.php");
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
	$Support.="</br>&nbsp;&nbsp;VHS $Type_VHS (n&deg;$Numero_VHS)" ;}

	if ($videotheque==2) {
	$nb_DVD++; $Numero_DVD=$Fiche['numero_de_classement']; 
	$Type_DVD=$Fiche['capacite_support'] ; $Proprio_DVD=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;&nbsp;DVD $Type_DVD (n&deg;$Numero_DVD)";}

	if ($videotheque==3) {
	$nb_CD++; $Numero_CD=$Fiche['numero_de_classement']; 
	$Type_CD=$Fiche['capacite_support'] ;  $Proprio_CD=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;&nbsp;CD $Type_CD (n&deg;$Numero_CD)";}
	
	if ($videotheque==4) {
	$nb_DVDBook++; $Numero_DVDBook=$Fiche['numero_de_classement']; 
	$Type_DVDBook=$Fiche['capacite_support'] ;  $Proprio_DVDBook=$Fiche['proprio_video']; 
	$Support.="</br>&nbsp;&nbsp;DVDBook $Type_DVDBook (n&deg;$Numero_DVDBook)";}

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

$Res=mysqli_query($db,"select max(id_film) as lemax, min(id_film) as lemin from $table "); 
 if (!$Res) { die('Invalid query: ' . mysqli_error($db)); }
$R=mysqli_fetch_assoc($Res) ;
$IdMax=$R['lemax'];
$IdMin=$R['lemin'];
mysqli_free_result($Res);
if ($id==$IdMax)  {$IdNext=1;}
if ($id==$IdMin)  {$IdPrev=$IdMax;}

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
			<table class="table">
				<tbody>
					<tr>
					<td >
					<div class="col-xs-6">
					
                    <?php if($Mypic==1) {echo "<a href=\"./Illustrations/$Image\" ><img  class=\"img-responsive\" style=\"width: 100%; max-width:600px\" src=\"./Illustrations/$Image\"   ></a>";} ?>
                    <!--</td> -->
					</div>
					
					<div class="col-xs-6">
					<!--<td> -->
						<div class="panel panel-info">
						<div class="panel-heading" ><h3><class="panel-title"><?php echo ($Titre);?></h3></div>
						<div class="panel-footer">
											<h6>
							<class="panel-body">
								<i>[<?php echo ($Title);?>]<br></i>
								<br>Realisateur : <?php echo ($Realisateur);?>
								<br>Origine : <?php echo ($Pays);?>
								<br>Genre : <?php echo ($Genre);?>
								<br>Annee : <?php echo ($Annee);?>
						</h6>
						</div>
						<div class="panel-body">
						<h5><?php echo ($LeResu);?></h5>


						</div>
						<div class="panel-footer"><h6>
						Dur&eacute;e :<?php echo "$Duree mn"; 
																	echo "<br/>";
																	echo "Support : $Support";
																	echo "<br/>"; ?></font>
						</h6></div>
						</div>
					

					</div>
					</td>
					</tr>
				</tbody>
			</table>
			</div>
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
				
			</table>
		</div>

		</div>
	</body>

</html>