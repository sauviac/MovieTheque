<?php
// Fonctions d'aide à la gestion de la base de donnees
//---------------------------------------------------
function trouver_liste_place_libre($db,$videotheque)
{
// Cette fonction crée la liste des places 
// disponibles dans la bibliotheque concernée
// ENTREE : id_videotheque (table videotheque)
// 1	VHS-thèque	vidéothèque des VHS
// 2	DVD-thèque	vidéothèque des DVD standards
// 3	CD-thèque	vidéothèque des CD DivX
// 4 	Classeur permettant de ranger CD ou DVD


 $RequeteSQL="select max(numero_de_classement) as max_val FROM emplacement where id_videotheque=$videotheque "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   
 
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  $Numero_max=0;
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $Numero_max=$Fiche['max_val'];
  }
 mysqli_free_result($Result);
$i=1;
$compte_libre=0;

$RequeteSQL="select distinct numero_de_classement FROM emplacement where id_videotheque= $videotheque order by numero_de_classement"; 
$Result=mysqli_query($db,$RequeteSQL);
if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $Numero=$Fiche['numero_de_classement'];
  if ($i !== $Numero) 
     {
	 $delta= $Numero - $i;
//	 if ($delta<0) {$delta=-$delta;}
 
 	 for ($k = 1; $k <= $delta; $k++){
	 		$Libre=$i+$k-1;
    		$Sortie.="<option  value=\"$Libre\" >$Libre</option>\n";
			$Return_var[$compte_libre+$k] =$Libre;
		 }
	 $compte_libre=$compte_libre+$delta;
	 }
	 $i=$Numero+1;
  }
mysqli_free_result($Result);
$Numero_max++;
if($compte_libre==0) { $Sortie.="<option  value=\"$Numero_max\" >$Numero_max</option>\n"; $compte_libre=1; $Return_var[$compte_libre] =$Numero_max;}

$Return_var[0]=$compte_libre;
$Return_var[$compte_libre+1]=$Sortie;

//  return $Sortie  ;
  return $Return_var ;
}

// ============================================================ //
function liste_genre($db)
{
// Cette fonction crée la liste des genres disponibles
// creation chaine charactere pour html


 $RequeteSQL="select id_genre, genre FROM genre order by genre "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $genre=$Fiche['genre'];
  $id=$Fiche['id_genre'];
     		$Sortie.="<option  value=\"$id\" >$genre</option>\n";
  }
 mysqli_free_result($Result);
  return $Sortie;
}


// ============================================================ //
function liste_pays($db)
{
// Cette fonction crée la liste des genres disponibles
// creation chaine charactere pour html
 $RequeteSQL="select id_pays, pays FROM pays order by id_pays "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $pays=$Fiche['pays'];
  $id_pays=$Fiche['id_pays'];
     		$Sortie.="<option  value=\"$id_pays\" >$pays</option>\n";
  }
 mysqli_free_result($Result);
  return $Sortie;
}

// ============================================================ //
function liste_rangement($db)
{
// Cette fonction crée la liste des rangements dispo
// creation chaine charactere pour html


 $RequeteSQL="select id_videotheque, nom_videotheque FROM videotheque order by nom_videotheque "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $id_videotheque=$Fiche['id_videotheque'];
  $nom_videotheque=$Fiche['nom_videotheque'];
     		$Sortie.="<option  value=\"$id_videotheque\" >$nom_videotheque</option>\n";
  }
 mysqli_free_result($Result);
  return $Sortie;
}
// ============================================================ //
// ============================================================ //
function liste_support_Book($db)
{
// creation de la liste des type de support video : les chs, les dvd, les cd, ...  
// pour construction de la combo box 
 $RequeteSQL="select id_type_support, type_de_support, capacite_support FROM type_support order by capacite_support "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
	$id=$Fiche['id_type_support'];
	$nom=$Fiche['type_de_support'] ." ". $Fiche['capacite_support'];
    if( $id==6) 
	{	$Sortie.="<option  value=\"$id\" selected >$nom</option>\n";} 
	else
	{	$Sortie.="<option  value=\"$id\" >$nom</option>\n";} 
  }
 mysqli_free_result($Result);
  return $Sortie;
}
// ============================================================ //
// ============================================================ //
function liste_support_VHS($db)
{
// creation de la liste des type de VHS  
// pour construction de la combo box 
// old version  $RequeteSQL="select id_type_support, capacite_support FROM type_support where type_de_support='VHS' order by capacite_support "; 
  $RequeteSQL="select id_type_support, type_de_support, capacite_support FROM type_support order by capacite_support "; 
  $Result=mysqli_query($db,$RequeteSQL);   
  $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $id=$Fiche['id_type_support'];
  //old version $nom=$Fiche['capacite_support'];
  $nom=$Fiche['type_de_support'] ." ". $Fiche['capacite_support'];
  if( $id==1) {	$Sortie.="<option  value=\"$id\" selected >$nom</option>\n";} 
  else{	$Sortie.="<option  value=\"$id\" >$nom</option>\n";} 
  
 }
 mysqli_free_result($Result);
  return $Sortie;
}

// ============================================================ //
// ============================================================ //
function liste_support_DVD($db)
{
// creation de la liste des type de DVD  
// pour construction de la combo box 
//OLD version  $RequeteSQL="select id_type_support, capacite_support FROM type_support where type_de_support='DVD' order by capacite_support "; 
 $RequeteSQL="select id_type_support, type_de_support, capacite_support FROM type_support order by capacite_support "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $id=$Fiche['id_type_support'];
 //old version  $nom=$Fiche['capacite_support'];
 	$nom=$Fiche['type_de_support'] ." ". $Fiche['capacite_support'];
    if( $id==6) 
	{	$Sortie.="<option  value=\"$id\" selected >$nom</option>\n";} 
	else
	{	$Sortie.="<option  value=\"$id\" >$nom</option>\n";} 
  }
  mysqli_free_result($Result);
  return $Sortie;
}
// ============================================================ //
// ============================================================ //
function liste_support_CD($db)
{
// creation de la liste des type de CD  
// pour construction de la combo box 
//OLD version $RequeteSQL="select id_type_support, capacite_support FROM type_support where type_de_support='CD' order by capacite_support "; 
 $RequeteSQL="select id_type_support, type_de_support, capacite_support FROM type_support order by capacite_support "; 
 $Result=mysqli_query($db,$RequeteSQL);   
 $Sortie="";   

if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
  
 while ( $Fiche = mysqli_fetch_assoc($Result) ) 
  {
  $id=$Fiche['id_type_support'];
//old version   $nom=$Fiche['capacite_support'];
$nom=$Fiche['type_de_support'] ." ". $Fiche['capacite_support'];
    if( $id==7) 
	{	$Sortie.="<option  value=\"$id\" selected >$nom</option>\n";} 
	else
	{	$Sortie.="<option  value=\"$id\" >$nom</option>\n";} 
  }
 mysqli_free_result($Result);
  return $Sortie;
}

?>