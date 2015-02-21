<?php 
require_once("./Fonc_Base.php");
$db= ouvrir_base ();

echo $_REQUEST['Origine'];

// Info pour fichimage.php
$table="movie";
$entete="Film"; 

$Couleur=array("B0E0E6" , "#F0F8FF");

//=====================================
//########### AJOUT ###################
//=====================================
if ( $_REQUEST['Origine']=="Ajout") 
{
 print_r($_REQUEST);
 
	include('fichImage.php');

	$Titre=$_REQUEST['Titre'];
	$Realisateur=$_REQUEST['Realisateur'];
	$Annee=$_REQUEST['Annee'];
	
	$Pays=$_REQUEST['Pays'];
	$nb_pays=count($Pays);  // contient le nombre de pays producteurs du film
	
	$Genre=$_REQUEST['Genre'];
	$nb_genre=count($Genre); // contient le nombre de genre
	
	$Duree=$_REQUEST['Duree'];
	$Resume=$_REQUEST['Resume'];
	$Title=$_REQUEST['Title'];  // titre original
	
	if (isset($_REQUEST['VHS'])) {
	$VHS=$_REQUEST['VHS'];
	$Numero_VHS=$_REQUEST['Numero_VHS']; // no de classement VHS
	if (($_REQUEST['Numero_libre_VHS'])!="") {$Numero_VHS=$_REQUEST['Numero_libre_VHS']; }// no LIBRE de classement VHS
	$Proprio_VHS=$_REQUEST['Proprio_VHS']; // proprietaire de la VHS
	$Type_VHS=$_REQUEST['Type_VHS'];
	} else {$VHS=0;}
		
	if (isset($_REQUEST['DVD'])) {
	$DVD=$_REQUEST['DVD'];
	$Numero_DVD=$_REQUEST['Numero_DVD']; // no de classement DVD
	if (($_REQUEST['Numero_libre_DVD'])!="") {$Numero_DVD=$_REQUEST['Numero_libre_DVD']; }// no LIBRE de classement dvd	
	$Proprio_DVD=$_REQUEST['Proprio_DVD']; // proprietaire de la DVD
	$Type_DVD=$_REQUEST['Type_DVD'];
	} else {$DVD=0;}

	if (isset($_REQUEST['CD'])) {
	$CD=$_REQUEST['CD'];
	$Numero_CD=$_REQUEST['Numero_CD']; // no de classement CD
	if (($_REQUEST['Numero_libre_CD'])!="") {$Numero_CD=$_REQUEST['Numero_libre_CD']; }// no LIBRE de classement CD		
	$Proprio_CD=$_REQUEST['Proprio_CD']; // proprietaire de la CD
	$Type_CD=$_REQUEST['Type_CD'];
	} else {$CD=0;}
	
	if (isset($_REQUEST['Book'])) {
	echo "BObook";
		$Book=$_REQUEST['Book'];
		$Numero_Book=$_REQUEST['Numero_Book']; // no de classement Book
		if (($_REQUEST['Numero_libre_Book'])!="") {$Numero_Book=$_REQUEST['Numero_libre_Book']; }// no LIBRE de classement Book		
		$Proprio_Book=$_REQUEST['Proprio_Book']; // proprietaire de la Book
		$Type_Book=$_REQUEST['Type_Book'];
		echo "\n No emplacement : ".$Numero_Book. "\n";
	} else {$Book=0;}

// == Requete mysql pour ajout des informations
// 1) Ajout du film
	$RequeteSQL=  "INSERT INTO movie (id_film, titre, realisateur, annee, duree, title, resume, image)";
	$RequeteSQL .= " VALUES ('', \"$Titre\", \"$Realisateur\", \"$Annee\", \"$Duree\", \"$Title\", \"$Resume\", \"$NomImage\")";
 	//echo $RequeteSQL;
 	$Result=mysqli_query($db,$RequeteSQL);
	 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
	echo "gestion 1"; 

// 2) Recup id_film
	$RequeteSQL=  "select max(id_film) from movie ;";  // Recherche du last_insert_id de la table
	//echo $RequeteSQL;
	 $Result=mysqli_query($db,$RequeteSQL);
	 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	 else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		$id_film=$Fiche['max(id_film)'];
		mysqli_free_result($Result);
	}
	echo "gestion 2";
	
// 3) Ajout du ou des genre(s) : tables est_du_genre 
for ($k = 0; $k < $nb_genre; $k++)
{
	$id_genre=$Genre[$k];
	//echo "<br> k = $k et $id_genre";
	$RequeteSQL=  "insert into est_du_genre (id_film, id_genre) values ($id_film, $id_genre);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
}
	echo "gestion 3";

// 4) Ajout du ou des pays(s) de production
for ($k = 0; $k < $nb_pays; $k++)
{
	$id_pays=$Pays[$k];
	//echo "<br> k = $k et $id_pays";
	$RequeteSQL=  "insert into est_produit_par_un_pays (id_film, id_pays) values ($id_film, $id_pays);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
}
	echo "gestion 4";

// 5) Ajout du ou des emplacements de rangement + renseignemenbt de [est_entregistre]
if ($VHS == 1) { //creation de emplacement de type VHS
echo "emplacement VHS";
    $RequeteSQL="select count(*) as compte from emplacement where id_videotheque=1 and numero_de_classement=\"$Numero_VHS\" ";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
	else 
	{
		$Fiche = mysqli_fetch_assoc($Result);
		mysqli_free_result($Result);	
		$compte=$Fiche['compte'];	
	}


	// $id_emplacement=$Numero_VHS;
	$numero_de_classement=$Numero_VHS;

	//if ($compte==0)  
	//{
		$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
		$RequeteSQL .= " VALUES ('', \"$Type_VHS\", \"$Numero_VHS\", 1, \"$Proprio_VHS\"  ) ;";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
		$RequeteSQL="select  LAST_INSERT_ID() as last;";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			mysqli_free_result($Result);
			$id_emplacement=$Fiche['last'];
		}
	//}
	$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}

}
if ($DVD == 1) { //creation de emplacement
echo "emplacement DVD";

    $RequeteSQL="select count(*) as compte from emplacement where id_videotheque=2 and numero_de_classement=\"$Numero_DVD\" ";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		mysqli_free_result($Result);
		$compte=$Fiche['compte'];
	}
	
	 $id_emplacement=$Numero_DVD;
	 
    // if ($compte==0)  
	//{//creation de emplacement
	$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
	$RequeteSQL .= " VALUES ('', \"$Type_DVD\", \"$Numero_DVD\", 2, \"$Proprio_DVD\"  ) ;";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
	$RequeteSQL="select  LAST_INSERT_ID() as last;";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		$id_emplacement=$Fiche['last'];
		mysqli_free_result($Result);
	}
	//}
	$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
}
if ($CD == 1) { //creation de emplacement
echo "emplacement CD";

    $RequeteSQL="select count(*) as compte from emplacement where id_videotheque=3 and numero_de_classement=\"$Numero_CD\" ";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		$compte=$Fiche['compte'];
		mysqli_free_result($Result);
	}
	
	$id_emplacement=$Numero_CD;
//	 echo "<BR>CD $id_emplacement";
	 
    // if ($compte==0)  
	 //{
	$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
	$RequeteSQL .= " VALUES ('', \"$Type_CD\", \"$Numero_CD\", 3, \"$Proprio_CD\"  ) ;";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
	$RequeteSQL="select  LAST_INSERT_ID() as last;";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		$id_emplacement=$Fiche['last'];
		mysqli_free_result($Result);
	}

	//}
	$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
}

if ($Book == 1) { //creation de emplacement
echo "emplacement BOOK";

    $RequeteSQL="select count(*) as compte from emplacement where id_videotheque=4 and numero_de_classement=\"$Numero_Book\" ";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	else
	{
		$Fiche = mysqli_fetch_assoc($Result);
		$compte=$Fiche['compte'];
		mysqli_free_result($Result);		
	}
	$id_emplacement=$Numero_Book;
	
//    if ($compte==0)  
	//{
		$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
		$RequeteSQL .= " VALUES ('', \"$Type_Book\", \"$Numero_Book\", 4, \"$Proprio_Book\"  ) ;";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
		$RequeteSQL="select  LAST_INSERT_ID() as last;";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			$id_emplacement=$Fiche['last'];
			mysqli_free_result($Result);		
		}

	//}
	
	$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
}

echo "gestion 5";

// ==== Fin gestion SQL, affichage des listes
	// select * from (movie m  join emplacement e on  m.id_film=e.id_film), est_du_genre eg, genre g where  g.id_genre=eg.id_genre

	$RequeteSQL="select distinct * from movie m, emplacement e, est_enregistre er, est_du_genre eg, genre g, type_support t, videotheque v where  m.id_film=er.id_film and m.id_film=eg.id_film and g.id_genre=eg.id_genre and t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement and v.id_videotheque=e.id_videotheque order by m.id_film desc ;";



	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	$Compte= mysqli_num_rows($Result) ; // Resultat Pour Affichage Final

}
//=====================================
//=====================================
//########### SUPPR ###################
//=====================================
//=====================================

if ( $_REQUEST['Origine']=="Suppr") 
{
	$id=$_REQUEST['id'];  // id de film enregistrement a effacer
	
  // On efface le film
	$RequeteSQL="DELETE FROM movie WHERE id_film=$id"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
   // On efface le genre du film
   
	$RequeteSQL="DELETE FROM est_du_genre WHERE id_film=$id"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
    // On efface le pays du film
	$RequeteSQL="DELETE FROM est_produit_par_un_pays WHERE id_film=$id"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
	// recup de l'emplacement
	$RequeteSQL="select id_emplacement from est_enregistre WHERE id_film=$id";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
	else 
	{
	$Fiche = mysqli_fetch_assoc($Result);
	$id_emplacement=$Fiche['id_emplacement'];	
	}
	
		// On efface l'enregistrement TABLE EST_ENREGISTRE
		$RequeteSQL="DELETE FROM est_enregistre WHERE id_film=$id"; 
		$Result=mysqli_query($db,$RequeteSQL);      
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}

		// On efface les empalcements TABLE emplacement
		$RequeteSQL="DELETE FROM emplacement WHERE id_emplacement=$id_emplacement";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
		//else {mysqli_free_result($Result);}
	
	// On affiche la liste
 
// ==== Fin gestion SQL, affichage des listes
	// select * from (movie m  join emplacement e on  m.id_film=e.id_film), est_du_genre eg, genre g where  g.id_genre=eg.id_genre
$RequeteSQL="select distinct * from movie m, emplacement e, est_enregistre er, est_du_genre eg, genre g, type_support t where  m.id_film=er.id_film and m.id_film=eg.id_film and g.id_genre=eg.id_genre and t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement order by m.id_film desc";

	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	$Compte= mysqli_num_rows($Result) ;   // Resultat Pour Affichage Final

}

//=====================================
//=====================================
//########### MODIF ###################
//=====================================
//=====================================
if ( $_REQUEST['Origine']=="Modif") 
{
  // 1) On récupère les données 

echo "ICI on modifie" ;
print_r($_REQUEST);

	$id_film=$_REQUEST['id'];
	$id=$id_film;
	
		include('fichImage.php');

	$Titre=$_REQUEST['Titre'];
	$Realisateur=$_REQUEST['Realisateur'];
	$Annee=$_REQUEST['Annee'];
	
	$Pays=$_REQUEST['Pays'];
	$nb_pays=count($Pays);  // contient le nombre de pays producteurs du film
	
	$Genre=$_REQUEST['Genre'];
	$nb_genre=count($Genre); // contient le nombre de genre
	
	$Duree=$_REQUEST['Duree'];
	$Resume=$_REQUEST['Resume'];
	$Title=$_REQUEST['Title'];  // titre original
	
	if (isset($_REQUEST['VHS'])) {
		$VHS=$_REQUEST['VHS'];
		$Numero_VHS=$_REQUEST['Numero_VHS']; // no de classement VHS
		if (($_REQUEST['Numero_libre_VHS'])!="") {$Numero_VHS=$_REQUEST['Numero_libre_VHS']; }// no LIBRE de classement VHS
		$Proprio_VHS=$_REQUEST['Proprio_VHS']; // proprietaire de la VHS
		$Type_VHS=$_REQUEST['Type_VHS'];
	} else {$VHS=0;}
		
	if (isset($_REQUEST['DVD'])) {
		$DVD=$_REQUEST['DVD'];
		$Numero_DVD=$_REQUEST['Numero_DVD']; // no de classement DVD
		if (($_REQUEST['Numero_libre_DVD'])!="") {$Numero_DVD=$_REQUEST['Numero_libre_DVD']; }// no LIBRE de classement dvd	
		$Proprio_DVD=$_REQUEST['Proprio_DVD']; // proprietaire de la DVD
		$Type_DVD=$_REQUEST['Type_DVD'];
	} else {$DVD=0;}

	if (isset($_REQUEST['CD'])) {
		$CD=$_REQUEST['CD'];
		$Numero_CD=$_REQUEST['Numero_CD']; // no de classement CD
		if (($_REQUEST['Numero_libre_CD'])!="") {$Numero_CD=$_REQUEST['Numero_libre_CD']; }// no LIBRE de classement CD		
		$Proprio_CD=$_REQUEST['Proprio_CD']; // proprietaire de la CD
		$Type_CD=$_REQUEST['Type_CD'];
	} else {$CD=0;}
	
	if (isset($_REQUEST['Book'])) {
		$Book=$_REQUEST['Book'];
		$Numero_Book=$_REQUEST['Numero_Book']; // no de classement Book
		if (($_REQUEST['Numero_libre_Book'])!="") {$Numero_Book=$_REQUEST['Numero_libre_Book']; }// no LIBRE de classement Book		
		$Proprio_Book=$_REQUEST['Proprio_Book']; // proprietaire de la Book
		$Type_Book=$_REQUEST['Type_Book'];
	} else {$Book=0;}

	$RequeteSQL="UPDATE movie SET titre=\"$Titre\" , realisateur=\"$Realisateur\", annee=\"$Annee\",   ";
	$RequeteSQL.=" duree=\"$Duree\",  title=\"$Title\", ";
	$RequeteSQL.=" resume=\"$Resume\" , ";
	$RequeteSQL.=" image=\"$NomImage\" "; 
	$RequeteSQL.=" WHERE id_film=$id_film ";
	echo $RequeteSQL;
	
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
 
  // 2) MODIF du ou des genre(s) : tables est_du_genre 
  
	// On efface le genre du film
	$RequeteSQL="DELETE FROM est_du_genre WHERE id_film=$id_film"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
	// on recrée les données
	for ($k = 0; $k < $nb_genre; $k++)
	{	
		$id_genre=$Genre[$k];
		$RequeteSQL=  "insert into est_du_genre (id_film, id_genre) values ($id_film, $id_genre);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
	}
echo "gestion 3";

  // 3) MODIF du ou des pays(s) de production
		// On efface le pays du film
	$RequeteSQL="DELETE FROM est_produit_par_un_pays WHERE id_film=$id"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
	// On re crée du ou des pays(s) de production
	for ($k = 0; $k < $nb_pays; $k++)
	{	
		$id_pays=$Pays[$k];
		$RequeteSQL=  "insert into est_produit_par_un_pays (id_film, id_pays) values ($id_film, $id_pays);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
	}
echo "gestion 4";

  // 4) MODIF EMPLACEMENT
		// On efface les emplacements utilisés par le film
		// recup id emplacement d'abord puis effacement
	$RequeteSQL="DELETE FROM est_enregistre WHERE id_film=$id"; 
	$Result=mysqli_query($db,$RequeteSQL);      
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	//else {mysqli_free_result($Result);}
	
	//On recrée les emplacements
	if ($VHS == 1) 
	{ //creation de emplacement de type VHS
		$RequeteSQL="select count(*) as compte from emplacement where id_videotheque=1 and numero_de_classement=\"$Numero_VHS\" ";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			$compte=$Fiche['compte'];
			mysqli_free_result($Result);			
		}

//	 $id_emplacement=$Numero_VHS;
		echo "<b><i>$Numero_VHS + compte=$compte</i></b>";
		
		if ($compte==0)  
		{
			$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
			$RequeteSQL .= " VALUES ('', \"$Type_VHS\", \"$Numero_VHS\", 1, \"$Proprio_VHS\"  ) ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
			
			$RequeteSQL="select  LAST_INSERT_ID() as last;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			else 
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['last'];
				mysqli_free_result($Result);			
			}

		}
		else
		{
			$RequeteSQL="select * from emplacement where id_videotheque=1 and numero_de_classement=\"$Numero_VHS\" ";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
			else 
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['id_emplacement'];
				mysqli_free_result($Result);
			}

			
			$RequeteSQL="Update emplacement set id_type_support=\"$Type_VHS\", numero_de_classement=\"$Numero_VHS\", id_videotheque=1, proprio_video=\"$Proprio_VHS\" where id_emplacement=$id_emplacement ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
		}
		
		$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}

	}
	
	if ($DVD == 1) 
	{ //creation de emplacement
		$RequeteSQL="select count(*) as compte from emplacement where id_videotheque=2 and numero_de_classement=\"$Numero_DVD\" ";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			$compte=$Fiche['compte'];
			mysqli_free_result($Result);			
		}

		//	 $id_emplacement=$Numero_DVD;
	 
		if ($compte==0)  
		{
			$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
			$RequeteSQL .= " VALUES ('', \"$Type_DVD\", \"$Numero_DVD\", 2, \"$Proprio_DVD\"  ) ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
			
			$RequeteSQL="select  LAST_INSERT_ID() as last;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			else 
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['last'];
				mysqli_free_result($Result);				
			}

			//	 echo "<BR>DVD $id_emplacement";
		}
		else
		{
			$RequeteSQL="select * from emplacement where id_videotheque=2 and numero_de_classement=\"$Numero_DVD\" ";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
			else
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['id_emplacement'];
				mysqli_free_result($Result);				
			}

			
			$RequeteSQL="Update emplacement set id_type_support=\"$Type_DVD\", numero_de_classement=\"$Numero_DVD\", id_videotheque=2, proprio_video=\"$Proprio_DVD\" where id_emplacement=$id_emplacement ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
		}


		$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}
	}
	
	if ($CD == 1) 
	{ //creation de emplacement
		$RequeteSQL="select count(*) as compte from emplacement where id_videotheque=3 and numero_de_classement=\"$Numero_CD\" ";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			$compte=$Fiche['compte'];
			mysqli_free_result($Result);			
		}

		//	 $id_emplacement=$Numero_CD;
		//	 echo "<BR>CD $id_emplacement";
	 
		if ($compte==0)  
		{
			$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
			$RequeteSQL .= " VALUES ('', \"$Type_CD\", \"$Numero_CD\", 3, \"$Proprio_CD\"  ) ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
			
			$RequeteSQL="select  LAST_INSERT_ID() as last;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); } 
			else
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['last'];
				mysqli_free_result($Result);	
			}
			
		}
		else
		{
			$RequeteSQL="select * from emplacement where id_videotheque=3 and numero_de_classement=\"$Numero_CD\" ";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
			else
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['id_emplacement'];
				mysqli_free_result($Result);				
			}

			
			$RequeteSQL="Update emplacement set id_type_support=\"$Type_CD\", numero_de_classement=\"$Numero_CD\", id_videotheque=3, proprio_video=\"$Proprio_CD\" where id_emplacement=$id_emplacement ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
		}

		$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}


	}
	
	if ($Book == 1) 
	{ //creation de emplacement
		$RequeteSQL="select count(*) as compte from emplacement where id_videotheque=4 and numero_de_classement=\"$Numero_Book\" ";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		else
		{
			$Fiche = mysqli_fetch_assoc($Result);
			$compte=$Fiche['compte'];
			mysqli_free_result($Result);			
		}

echo $compte."BOOK1".$RequeteSQL;
		if ($compte==0)  
		{
			$RequeteSQL="insert into emplacement (id_emplacement, id_type_support, numero_de_classement, id_videotheque, proprio_video)";
			$RequeteSQL .= " VALUES ('', \"$Type_Book\", \"$Numero_Book\", 4, \"$Proprio_Book\"  ) ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
			
			$RequeteSQL="select  LAST_INSERT_ID() as last;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			else
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['last'];
				mysqli_free_result($Result);				
			}

echo "BOOK2".$RequeteSQL;
		}
		else
		{
			$RequeteSQL="select * from emplacement where id_videotheque=4 and numero_de_classement=\"$Numero_Book\" ";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }  
			else
			{
				$Fiche = mysqli_fetch_assoc($Result);
				$id_emplacement=$Fiche['id_emplacement'];
				mysqli_free_result($Result);				
			}

			
			$RequeteSQL="Update emplacement set id_type_support=\"$Type_Book\", numero_de_classement=\"$Numero_Book\", id_videotheque=4, proprio_video=\"$Proprio_Book\" where id_emplacement=$id_emplacement ;";
			$Result=mysqli_query($db,$RequeteSQL);
			if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
			//else {mysqli_free_result($Result);}
echo "BOOK3.$RequeteSQL";
		}

		$RequeteSQL="insert into est_enregistre  (id_emplacement, id_film) values ($id_emplacement, $id_film);";
		$Result=mysqli_query($db,$RequeteSQL);
		if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
		//else {mysqli_free_result($Result);}

echo "BOOK4.$RequeteSQL";
	}

	echo "gestion 5";

	$RequeteSQL="select distinct * from movie m, emplacement e, est_enregistre er, est_du_genre eg, genre g, type_support t where  m.id_film=er.id_film and m.id_film=eg.id_film and g.id_genre=eg.id_genre and t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement order by m.id_film";
	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	else
	{
		$Compte= mysqli_num_rows($Result) ;
		// mysqli_free_result($Result);		// ne pas mettre sinon pas de resultat dans la partie html
	}


} // fin gestion des modifs

// ==========================================
//########### ACCUEIL ###################
// ==========================================
if ( $_REQUEST['Origine']=="Accueil") 
{
$Ord="m.id_film ASC";
if (isset ($_REQUEST['Ordre']) ) 
	{
	$Ord = $_REQUEST['Ordre'] ;
	}
$RequeteSQL="select distinct * from movie m, emplacement e, est_enregistre er, est_du_genre eg, genre g, type_support t, videotheque v where  m.id_film=er.id_film and m.id_film=eg.id_film and g.id_genre=eg.id_genre and t.id_type_support=e.id_type_support and e.id_emplacement=er.id_emplacement and e.id_videotheque=v.id_videotheque ";
 
$RequeteSQL.=" order by $Ord";

$Result=mysqli_query($db,$RequeteSQL);
 if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
 else
 {
	$Compte= mysqli_num_rows($Result) ;
	//mysqli_free_result($Result); sinon pas de result dans le html
 }

}

//########### SELECTION ###################
if ( $_REQUEST['Origine']=="Selection") 
{

	$Titre=STRTOUPPER( $_REQUEST['Titre'] );
	$Realisateur=$_REQUEST['Realisateur'];
	$Annee=$_REQUEST['Annee'];
 
 	$Genre=$_REQUEST['Genre'];
	$Duree=$_REQUEST['Duree'];
	$Title=STRTOUPPER( $_REQUEST['Title'] );
	$Support=$_REQUEST['Support'];
	
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
	{ $Ord="m.titre"; }
	
	
	if ($Suite==0) 
	{	$RequeteSQL.="order by $Ord";}
	else
	{	$RequeteSQL.=" $Selec order by $Ord";}


echo "<b>$RequeteSQL</b>";

	$Result=mysqli_query($db,$RequeteSQL);
	if (!$Result) { die('Invalid query: ' . mysqli_error($db)); }
	
	else 
	{
		$Compte= mysqli_num_rows($Result) ;
		//mysqli_free_result($Result); // ne pas mettre ici on s'en sert plus loin
	}
 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta name="generator" content="Adobe GoLive 6">
		<title>Movieth&egrave;que</title>
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
		<h1>Movieth&egrave;que 
		  <noedit><?php echo ": $Compte films s&eacute;lectionn&eacute;s";?></noedit></h1>
		<hr>
		<table width="716" border="0" cellspacing="5" cellpadding="0">
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
						<a href="MovieAjout.php">Ajouter une fiche</a></div>
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
		</div>
		<div align="left">
			<table border="0" cellspacing="4" cellpadding="0">
				<tr align="center">
					<td align="center" valign="middle" width="12">
					<h3><a href="MovieListe.php?Origine=Accueil&Ordre=m.id_film ASC">id&deg;</a>
                    </br>
					<?php
					$lepostPlus="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=&Ordre=m.id_film asc";
					$lepostMoins="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=&Ordre=m.id_film desc";
					if ($_REQUEST['Origine']=="Selection" and ($Support==1 or $Support==2 or $Support==3 or $Support==4 )) 
					{
						if ($Support==3)
						{
							$lepostPlus="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=3&Ordre=m.id_film asc";
							$lepostMoins="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=3&Ordre=m.id_film desc";
						}
						if ($Support==2)
						{
							$lepostPlus="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=2&Ordre=m.id_film asc";
							$lepostMoins="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=2&Ordre=m.id_film desc";
						}
						if ($Support==1)
						{
							$lepostPlus="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=1&Ordre=m.id_film asc";
							$lepostMoins="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=1&Ordre=m.id_film desc";
						}
						if ($Support==4)
						{
							$lepostPlus="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=4&Ordre=m.id_film asc";
							$lepostMoins="MovieListe.php?Origine=Selection&Titre=&Realisateur=&Annee=&Genre=&Duree=&Title=&Support=4&Ordre=m.id_film desc";
						}
					}
					?>
					<a href="<?php echo $lepostPlus; ?>">(+)</a>&nbsp;&nbsp;
					<a href="<?php echo $lepostMoins; ?>">(-)</a>&nbsp;&nbsp;
                    </h3>
					</td>
					<td align="center" valign="middle" width="20"></td>
					<td align="center" valign="middle" width="20"></td>
					<td align="center" valign="middle" width="20"></td>
					<td align="center" valign="middle" width="65">
						<h3>Biblio</br>
						<a href="MovieListe.php?Origine=Accueil&Ordre=v.id_videotheque asc, e.numero_de_classement ASC">(+)</a>&nbsp;&nbsp;
						<a href="MovieListe.php?Origine=Accueil&Ordre=v.id_videotheque desc, e.numero_de_classement DESC">(-)</a>&nbsp;&nbsp;
						</h3>
					</td>
					<td align="center" valign="middle" width="65">
						<h3>Support</br>
						<a href="MovieListe.php?Origine=Accueil&Ordre=t.type_de_support asc, e.numero_de_classement ASC">(+)</a>&nbsp;&nbsp;
						<a href="MovieListe.php?Origine=Accueil&Ordre=t.type_de_support desc, e.numero_de_classement DESC">(-)</a>&nbsp;&nbsp;
						</h3>
					</td>
					<td align="center" valign="middle" width="65">
						<h3>N&deg;</br>
						<a href="MovieListe.php?Origine=Accueil&Ordre=e.numero_de_classement ASC, v.id_videotheque asc">(+)</a>&nbsp;&nbsp;
						<a href="MovieListe.php?Origine=Accueil&Ordre=e.numero_de_classement DESC, v.id_videotheque desc">(-)</a>&nbsp;&nbsp;
						</h3>
					</td>
					<td align="center" valign="middle">
						<h3>  <a href="MovieListe.php?Origine=Accueil&Ordre=m.titre ASC">titre</a>   </h3>
					</td>
					<td align="center" valign="middle">
						<h3>Genre</h3>
					</td>
					<td align="center" valign="middle" width="50">
						<h3>Dur&eacute;e</h3>
					</td>
					<td align="center" valign="middle">
						<h3>R&eacute;alisateur</h3>
					</td>
					<td align="center" valign="middle">
						<h3>Ann&eacute;e</h3>
					</td>
					<td align="center" valign="middle" width="50">
						<h3>Proprio</h3>
					</td>
					<td align="center" valign="middle" width="83">
						<h3>Modèle</h3>
					</td>
					<td align="center" valign="middle">
						<h3>Titre original</h3>
					</td>
					<td align="center" valign="middle">
						<h3>R&eacute;sum&eacute;</h3>
					</td>
					<td align="center" valign="middle">
						<h3>Image</h3>
					</td>

				</tr>
				<?php	

					$Ncoul=1;

					while ( $Fiche = mysqli_fetch_assoc($Result) ) 
					{ 
						if ($Ncoul==0) {$Ncoul=1;} else {$Ncoul=0;}
						// Recup des données
						$id=$Fiche['id_film'];
						$Titre=$Fiche['titre'];
						$Realisateur=$Fiche['realisateur'];
						$Annee=$Fiche['annee'];
						$Duree=$Fiche['duree'];
						$Title=$Fiche['title'];
						$Resume=$Fiche['resume'];
						$LeResu=nl2br($Resume);
						$Image=$Fiche['image'];
						$Genre=$Fiche['genre'];
						$Support=$Fiche['type_de_support'];
						$Numero=$Fiche['numero_de_classement'];
						$Proprio=$Fiche['proprio_video'];
						$Capa=$Fiche['capacite_support'];
						$Videotheque=$Fiche['id_videotheque'];
						// remplissage HTML
						echo "<tr bgcolor=\"$Couleur[$Ncoul]\"><td><p>$id<a name=\"$id\"></a></p></td>";
						echo "<td width=\"20\"><p><a href=\"./MovieModif.php?id=$id\"><img src=\"Web_image/Crayon.jpg\" alt=\"\" height=\"23\" width=\"20\" border=\"0\"></a></p></td>";
						echo "<td width=\"20\"><p><a onclick=\"return ValidClick()\" href=\"./MovieListe.php?Origine=Suppr&id=$id \"><img src=\"Web_image/trash.gif\" alt=\"\" height=\"20\" width=\"21\" border=\"0\"></a></p></td>";
						echo "<td width=\"20\"><p><a href=\"./MovieFiche.php?id=$id\"><img src=\"Web_image/FlecheD.gif\" alt=\"\" border=\"0\"></a></p></td>";
						echo "<td  align=\"right\"><p>$Videotheque</p></td>";
						echo "<td  align=\"right\"><p>$Support</p></td>";
						echo "<td  align=\"right\"><p>($Numero)</p></td>";
						echo "<td valign=\"middle\"><p>$Titre</p></td>";
						echo "<td><p>$Genre</p></td>";
						echo "<td><p>$Duree mn</p></td>";
						echo "<td><p>$Realisateur</p></td>";
						echo "<td><p>$Annee</p></td>";
						echo "<td><p>$Proprio</p></td>";
						echo "<td><p>$Capa</p></td>";
						echo "<td><p>$Title</p></td>";
						if ( $Resume != "") { $Texte="xxx";} else {$Texte="";}
						echo "<td><p>$Texte</p></td>";
						echo "<td><p>$Image</p></td>";
						echo "</tr>\n";
 					}
					mysqli_free_result($Result);
 					fermer_base ($db);

 				?>
			</table>
			<p></p>
			<p></p>
		</div>
	</body>

</html>