<?php 
function le_genre($genre)
{
$Sortie[1]=20;  // autres
 $RequeteSQL="select id_genre FROM genre where locate(genre,'$genre') ";
   $Result=mysql_query($RequeteSQL);
   if (!$Result) { die('Invalid query: ' . mysql_error()); }
   $Compte= mysql_num_rows($Result) ;
$Sortie[0]=$Compte;
if($Compte==0) {$Sortie[0]=1;}
   for ($k = 1; $k <= $Compte ; $k++) {$Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ; $id=$Fiche['id_genre']; $Sortie[$k]=$id; }
 return $Sortie;
}
// ==============================================
function le_pays($pays)
{
$Sortie[1]=9; // inconnu
   $RequeteSQL="select id_pays FROM pays where locate(pays,'$pays')!=0 or locate(description_pays,'$pays')!=0";
   $Result=mysql_query($RequeteSQL);
   if (!$Result) { die('Invalid query: ' . mysql_error()); }
   $Compte= mysql_num_rows($Result) ;
$Sortie[0]=$Compte;
if($Compte==0) {$Sortie[0]=1;}
   for ($k = 1; $k <= $Compte ; $k++) {$Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ; $id=$Fiche['id_pays']; $Sortie[$k]=$id; }  
return $Sortie ;
}
// ==============================================
function le_support($support)
{
$nb=1;
$Sortie[1]=0;
   $RequeteSQL="select id_type_support FROM type_support where  
   (instr('$support', 'VHS')!=0 and instr('$support', capacite_support)!=0) 
   or (instr('$support', 'VHS')=0 and instr('$support', type_de_support)!=0)  ";
   $Result=mysql_query($RequeteSQL);
   if (!$Result) { die('Invalid query: ' . mysql_error()); }
   $Compte= mysql_num_rows($Result) ;
 if ($Compte==1) {    $Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ; $id=$Fiche['id_type_support']; $Sortie[1]=$id; }
 
return $Sortie ;
}
// ================================================
require_once("./Fonc_Base.php");
$db= ouvrir_base ();
echo "Début <br>";

	$RequeteSQL=  "select * from dessin_anime ";
 	$Result=mysql_query($RequeteSQL);
	 while ( $Fiche = mysql_fetch_array($Result, MYSQL_ASSOC) ) 
{ // ouverture boucle tous les films
	$id=$Fiche['id'];
	$Numero=intval($Fiche['Numero'])+500;
	$Titre=$Fiche['Titre'];
	$Realisateur=$Fiche['Realisateur'];
	$Annee=$Fiche['Annee'];
	$Pays=$Fiche['Pays'];
	$Genre=$Fiche['Genre'];	
	$Duree=$Fiche['Duree'];		
	$Support=$Fiche['Support'];
	$Title=$Fiche['Title'];
	$Resume=$Fiche['Resume'];
	$Image=$Fiche['Image'];
  $lepays=le_pays($Pays);
  $legenre=le_genre($Genre);
  $lesupport=le_support($Support);
  echo "<b>$lesupport[1]</b>";
$theque=1;
if ($lesupport[1]==6) {$theque=2 ;}
if ($lesupport[1]==7) {$theque=3 ;}

// 1) Ajout du film
	$RequeteSQL=  "INSERT INTO movie (id_film, titre, realisateur, annee, duree, title, resume, image)";
	$RequeteSQL .= " VALUES ('', \"$Titre\", \"$Realisateur\", \"$Annee\", \"$Duree\", \"$Title\", \"$Resume\", \"$Image\")";
 	$Result1=mysql_query($RequeteSQL);
	 if (!$Result1) { die('Invalid query: ' . mysql_error()); }
	 
// 2) Recup id_film
	$RequeteSQL=  "select max(id_film) from movie ;";  // Recherche du last_insert_id de la table
	 $Result2=mysql_query($RequeteSQL);
	 if (!$Result2) { die('Invalid query: ' . mysql_error()); }
	 $Fiche2 = mysql_fetch_array($Result2, MYSQL_ASSOC);
	 $id_film=$Fiche2['max(id_film)'];
	 
// 3) Ajout du ou des genre(s) : tables est_du_genre 
for ($k = 1; $k <= $legenre[0] ; $k++)
{	$id_genre=$legenre[$k];
	$RequeteSQL=  "insert into est_du_genre (id_film, id_genre) values ($id_film, $id_genre);";
	$Result3=mysql_query($RequeteSQL);
	if (!$Result3) { die('Invalid query: ' . mysql_error()); }
}
// 4) Ajout du ou des pays(s) de production
for ($k = 1; $k <= $lepays[0]; $k++)
{	$id_pays=$lepays[$k];
	$RequeteSQL=  "insert into est_produit_par_un_pays (id_film, id_pays) values ($id_film, $id_pays);";
	$Result4=mysql_query($RequeteSQL);
	if (!$Result4) { die('Invalid query: ' . mysql_error()); }
}
// 5) Ajout du ou des emplacements de rangement + renseignemenbt de [est_entregistre]
	$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
	$RequeteSQL .= " VALUES ('', \"$lesupport[1]\", \"$Numero\", \"$theque\", 'Bruno'  ) ";
	$Result5=mysql_query($RequeteSQL);
	if (!$Result5) { die('Invalid query: ' . mysql_error()); }
	$RequeteSQL="select  LAST_INSERT_ID() as last;";
	$Result5=mysql_query($RequeteSQL);
	if (!$Result5) { die('Invalid query: ' . mysql_error()); }
	 $Fiche5 = mysql_fetch_array($Result5, MYSQL_ASSOC);
	 $id_emplacement=$Fiche5['last'];
	
	$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
	$Result5=mysql_query($RequeteSQL);
	if (!$Result5) { die('Invalid query: ' . mysql_error()); }

 // 6) Type de support
  
for($k=1;$k<=$lepays[0];$k++){echo "$id -- $Titre    $Pays - $lepays[$k] <br>"; }

for($k=1;$k<=$legenre[0];$k++){echo "$id -- $Titre    $Genre - $legenre[$k] <br>"; }


} // fin boucle sur tous les films




fermer_base ($db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Document sans titre</title>
</head>

<body>
</body>
</html>
