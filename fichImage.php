<?php


//$entete="Film"; 
$table="movie";

$uploaddir = './Illustrations/';

// le champ contenant le nom du fichier doit s'appeler "Image"
if (isset($_FILES['Image']['name'] ))
{  $NomImage=basename( $_FILES['Image']['name'] );
  
  // Verif de l'extension pour raison de securite
  			$LOC = $_FILES['Image']['name'];
			$extension = substr($LOC, strrpos($LOC, "."));
		// echo "l extension du fichier est ...  :  ".$extension."<br>";
			if(strstr($extension,"php")) die("Les fichiers en .ph* ne sont pas autorisés.");
			
echo $LOC; $NomImage;
// Recherche du last_insert_id de la table
	$REQ_ID=mysql_query("select max(id_film) from $table");
	$RES_ID=mysql_fetch_array($REQ_ID, MYSQL_ASSOC);
	$NEXT_ID=$RES_ID['max(id_film)']+1;
		

// LE NOM DU FICHIER SUR LE SITE EST COMPOSE :
//  d'une ENTETE (voir variable $entete
//  de l'ID de l'enregistrement correspondant
//  de l'extension du fichier jpg, gif, png etc...
} else
{$NomImage="";echo "x $NomImage x"; $LOC="";}
	 
if ($_REQUEST['Origine']=="Ajout")
{
// CAS ON AJOUTE UN NOUVEAU FICHIER
// **************************************************	

   // Si le champ fichier est rempli, On ajoute le fichier on crée son nom a partir du $id de la table
   	if (strlen($NomImage)!=0) 	{ $NomImage=$entete.$NEXT_ID.$extension ; }
  
} 
else 
{

// CAS ON MODIFIE UN  FICHIER
// **************************************	

	// on se trouve dans le cas de la modification, on peut donc vouloir ecraser le fichier precedent par une nouvelle version.
	// On recupere dans la base de donnees l'ancien nom de fichier
		   $RES3=mysql_query("select image from $table where id_film=$id_film");
	   
		   if (!$RES3) { die('Invalid query: ' . mysql_error()); } // si probleme de requete on arrete tout
		   else 	{
		   			$LIG=mysql_fetch_array($RES3, MYSQL_ASSOC);
       				$NomFichierAncien=$LIG['image'];
       				// echo "<br>ancien nom fichier = ".$NomFichierAncien ."<br>";
       				
       			// On verifie qu'un fichier existait precedemment dans la Bdata
       				if (strlen(	$NomFichierAncien ) !=0 ) // le champ fichier était rempli
       				 {
       					//On remplace le fichier mais on conserve le nom initial comme cela on ecrase la version
       					// precedente quoiqu'il arrive sauf si on n'a pas saisi de nom de fichier
       						if (strlen($LOC)!=0) {$NomImage=$entete.$id.$extension ;}
       						else {$NomImage=$NomFichierAncien;}	
       				 } else
       				 { // on cree un fichier si besoin
       				 
       				    if (strlen($NomImage)!=0) 	{
   							// Si le champ fichier est rempli, On ajoute le fichier on crée son nom a partir du $id de la table
   							$NomImage=$entete.$id.$extension ;
   							}
       				 
       				 }
       				
		   		}
							  

}

  
if (strlen($LOC)!=0) {
	$uploadfile = $uploaddir . $NomImage;
		
	if (move_uploaded_file($_FILES['Image']['tmp_name'], $uploadfile)) 
			{
//    			echo "Le fichier est valide, et a été téléchargé 
//           		avec succès. Voici plus d'informations :\n";
//			print_r($_FILES);    //	echo 'Voici quelques informations de déboguage :';

			} 
	else 	{
    			echo "<b>Problème téléchargement de fichiers. </b>
         		 Voici plus d'informations :\n";
       		 print_r($_FILES);    //	echo 'Voici quelques informations de déboguage :';
			}

 }
?>