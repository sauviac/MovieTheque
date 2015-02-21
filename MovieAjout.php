<?php 
require_once("./Fonc_Base.php");
require_once("./Fonc_gestion_BASE.php");
$db=ouvrir_base ();

// RECUP DES PLACES LIBRES //
$places_VHS=trouver_liste_place_libre($db,1);
$places_DVD=trouver_liste_place_libre($db,2);
$places_CD=trouver_liste_place_libre($db,3);
$places_Book=trouver_liste_place_libre($db,4);

//Old version -> New version il faut passer la $db pour 
// la nouvelle version mysqli qui a besoin de l'argument database
$liste_support_VHS=liste_support_VHS($db);
$liste_support_DVD=liste_support_DVD($db);
$liste_support_CD=liste_support_CD($db);
$liste_support_Book=liste_support_Book($db);

$liste_des_pays = liste_pays($db);
$liste_des_genres = liste_genre($db) ;

fermer_base ($db);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<title>Movie Ajout</title>
		<meta http-equiv="content-type" content="text/html" ; charset=iso-8859-1>
		<link href="main.css" rel="stylesheet" media="screen">
    
    <script type="text/javascript"><!--
function ValidClick()
{
return confirm("Etes-vous sur de vouloir supprimer ?");
}
function form_verif()	
{
	//alert(document.forms['Ajout'].Titre.value);

	
//	if(document.forms['Ajout'].Genre[].value=="")
//	{ 
//		alert("veuillez choisir un genre de film");
		
//		return false;
//	}
//	else
//	{	return true; }
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
for (i=0;i<nb;i++) { 
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
for (i=0;i<nb;i++) { 
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
var txt =<?php 
$ST= " new Array ('$places_CD[1]'";
for ($k = 2; $k <= $places_CD[0]; $k++) 
{
$ST .= ", '$places_CD[$k]'";
}
$ST .=");";
echo $ST;
?>;

document.forms['Ajout'].Numero_CD.options.length = nb;
for (i=0;i<nb;i++) { 
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
for (i=0;i<nb;i++) { 
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
		<h1>Vid&eacute;oth&egrave;que : <font color="blue">Nouvelle Ie</font></h1>
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
			<!-- <div align="center">  -->
			<hr>
		<form action="MovieListe.php" method="post" name="Ajout" onsubmit="form_verif();" enctype="multipart/form-data">
			<div align="left">
			<input type="hidden" name="Origine" value="Ajout">
			
			<table>
			<tr>
				<td>
			<table width="469" border="0" cellspacing="4" cellpadding="0" bgcolor="#CCFFFF">
				<tr>
					<td width="152">
						<p>Titre du film</p>							</td>
					<td>
						<p><input name="Titre" type="text" size="47"></p>							
					</td>
				</tr>
				<tr>
					<td width="152">
						<p>R&eacute;alisateur</p>							
					</td>
					<td>
						<p><input type="text" name="Realisateur" size="47"></p>							
					</td>
				</tr>
				<tr>
					<td width="152">
						<p>Ann&eacute;e</p>							</td>
					<td><input type="text" name="Annee" size="4"></td>
				</tr>
				<tr>
					<td width="152">
						<p>Pays</p>							
					</td>
					<td>
						<p><select name="Pays[]" size="3" multiple>
						<?php echo $liste_des_pays; ?>
						</select></p>							
					</td>
				</tr>
				<tr>
					<td width="152">
						<p>Genre</p>							
					</td>
					<td>
							<p><select name="Genre[]" multiple size="3">
							<?php echo $liste_des_genres ;?>
							</select>
							</p>						  
					</td>
				</tr>
				<tr>
							<td width="152">
								<p>Dur&eacute;e (en minutes)</p>							</td>
							<td>
								<p><input type="text" name="Duree" size="4"></p>							</td>
						</tr>
				<tr>
							<td valign="top" width="152">
								<p>R&eacute;sum&eacute; du cartoon</p>							</td>
							<td><textarea name="Resume" rows="8" cols="46"></textarea></td>
						</tr>
				<tr>
							<td colspan="2">
								<p>								</p>							</td>
						</tr>
				<tr>
							<td width="152">
								<p>Titre original</p>							</td>
							<td>
								<p><input type="text" name="Title" size="41"></p>							</td>
						</tr>
				<tr>
                          <td><p>Affiche du film</p></td>
						  <td><input name="Image" type="file" ></td>
				</tr>
			</table>
				</td>
				<td align="bottom" valign="bottom">
			<Table bgcolor="#CCFFFF">
				<tr>
					<td bgcolor="#99FFFF"><p><strong>Classement </strong></p></td>
				</tr>
				<tr>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="VHS" type="checkbox" id="VHS" onChange="if(!document.forms['Ajout'].VHS.checked) {selectVHS_OFF();} else  {selectVHS();}" value="1">
										VHS-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="DVD" type="checkbox" id="DVD" onChange="if(!document.forms['Ajout'].DVD.checked) {selectDVD_OFF();} else  {selectDVD();}" value="1">
										DVD-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="CD" type="checkbox" id="CD" onChange="if(!document.forms['Ajout'].CD.checked) {selectCD_OFF();} else  {selectCD();}" value="1">
										CD-Box</strong></p>
									</td>
									<td bgcolor="#99FFFF">
										<p align="left"><strong><input name="Book" type="checkbox" id="Book" onChange="if(!document.forms['Ajout'].Book.checked) {selectBook_OFF();} else  {selectBook();}" value="1">
										DVD-CD-Book</strong></p>
									</td>
				</tr>
				<tr>							
										<td><p align="center">N&deg; d'enregistrement<br>
											<select name="Numero_VHS" size="1" disabled id="Numero_VHS">
											<option>x</option>
											</select>
											<br>
											<label>Eventuellement <br>
													saisie libre <br>
											<input name="Numero_libre_VHS" type="text" disabled id="Numero_libre_VHS" size="5" maxlength="4">
											</label></p>                              
										</td>
										<td><p align="center">N&deg; d'enregistrement<br>
											<select name="Numero_DVD" size="1" disabled id="Numero_DVD">
											<option>x</option>
											</select><br>
											<label>Eventuellement <br>
											saisie libre <br>
											<input name="Numero_libre_DVD" type="text" disabled id="Numero_libre_DVD" size="5" maxlength="4">
											</label></p>
										</td>
										<td><p align="center">N&deg; d'enregistrement<br>
											<select name="Numero_CD" size="1" disabled id="Numero_CD">
											<option>x</option>
											</select>
											<br>
											<label>Eventuellement <br> saisie libre <br>
											<input name="Numero_libre_CD" type="text" disabled id="Numero_libre_CD" size="5" maxlength="4">
											</label></p>
										</td>
										<td><p align="center">N&deg; d'enregistrement<br>
											<select name="Numero_Book" size="1" disabled id="Numero_Book">
											<option>x</option>
											</select>
											<br>
											<label>Eventuellement <br>
													saisie libre <br>
											<input name="Numero_libre_Book" type="text" disabled id="Numero_libre_Book" size="5" maxlength="4">
											</label></p>                              
										</td>
				</tr>
								
				<tr><!-- Zone d'appartenance de la vidéo -->
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_VHS" size="1" disabled id="Proprio_VHS" (Oui>
										<option selected></option>
										<option value="Bruno" selected>Bruno</option>
										<option value="Benjamin">Benjamin</option>
										<option value="Isabelle">Isabelle</option>
										<option value="Valentin">Valentin</option>
                                        </select></p>                              	
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_DVD" size="1" disabled id="Proprio_DVD">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
										</select></p>
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_CD" size="1" disabled id="Proprio_CD">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
										</select></p>
									</td>
									<td><p align="center">appartient &agrave;<br>
										<select name="Proprio_Book" size="1" disabled id="Proprio_Book">
											<option selected></option>
											<option value="Bruno" selected>Bruno</option>
											<option value="Benjamin">Benjamin</option>
											<option value="Isabelle">Isabelle</option>
											<option value="Valentin">Valentin</option>
										</select></p>
									</td>
				</tr>
								<!-- Fin zone appartenance ... -->
								
				<tr><!-- Zone type d'enregistrement E120, DVD, ... -->
									<td><p align="center">mod&egrave;le<br>
										<select name="Type_VHS" size="1" disabled id="Type_VHS"><?php echo $liste_support_VHS;?>
										</select></p>
									</td>
									<td><p align="center">mod&egrave;le<br>
										<select name="Type_DVD" size="1" disabled id="Type_DVD">
											<?php echo $liste_support_DVD;?>
										</select></p>
									</td>
									<td><div align="center">
										<p>mod&egrave;le<br>
										<select name="Type_CD" size="1" disabled id="Type_CD">
												<?php echo $liste_support_CD;?>
										</select></p>
										</div>
									</td>
									<td><div align="center">
										<p>mod&egrave;le<br>
										<select name="Type_Book" size="1" disabled id="Type_Book">
												<?php echo $liste_support_Book;?>
										</select></p>
										</div>
									</td>
				</tr>
					<!-- FIN Zone type d'enregistrement E120, DVD, ... -->
				<tr>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
				<tr>
				  <td><input type="reset"></td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td><input type="submit" OnClick="submit();" name="submitButtonName"></td>
			  </tr>
                          </table>
						  
				</td>
			</tr>
			<tr>
							<td width="152">&nbsp;</td>
							<td>&nbsp;</td>
			</tr>
			</table>
			  </div>
		 </form>
		  <p></p>
		<!-- </div>  -->
		
    </body>

</html>