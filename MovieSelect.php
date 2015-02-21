<?php 
require_once("./Fonc_Base.php");
require_once("./Fonc_gestion_BASE.php");
$db= ouvrir_base ();

$liste_support_VHS=liste_support_VHS($db);
$liste_support_DVD=liste_support_DVD($db);
$liste_support_CD=liste_support_CD($db);

$liste_des_genres = liste_genre($db) ;

fermer_base ($db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

	<head>
		<meta name="generator" content="Adobe GoLive 6">
		<title>Vid&eacute;oth&egrave;que</title>
		<meta http-equiv="content-type" content="text/html;; charset=iso-8859-1">
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
		<h1>Vid&eacute;oth&egrave;que : <font color="blue">S&eacute;l&eacute;ction fiche</font></h1>
		<hr>
		<table width="318" border="0" cellspacing="5" cellpadding="0">
			<tr>
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
			<form action="MovieListe.php" method="get" name="Selection" enctype="multipart/form-data">
		  <div align="left">
					<input type="hidden" name="Origine" value="Selection">
					<table width="469" border="0" cellspacing="4" cellpadding="0" bgcolor="#f5f5dc">
						<tr>
							<td width="152">
								<p>Titre ou mot du titre</p>							</td>
							<td>
								<p><input type="text" name="Titre" size="47"></p>							</td>
						</tr>
						<tr>
							<td width="152">
								<p>R&eacute;alisateur</p>							</td>
							<td>
								<p><input type="text" name="Realisateur" size="47"></p>							</td>
						</tr>
						<tr>
							<td width="152">
								<p>Ann&eacute;e</p>							</td>
							<td><input type="text" name="Annee" size="4"></td>
						</tr>
						<tr>
							<td width="152">
								<p>Genre</p>							</td>
							<td>
								<p><select name="Genre" size="1">
									<option value="" selected>Tout</option>
									<?php echo $liste_des_genres ;?>
									</select></p>							</td>
						</tr>
						<tr>
							<td width="152">
								<p>Dur&eacute;e MAX (en minutes)</p>							</td>
							<td>
								<p><input type="text" name="Duree" size="4"></p>							</td>
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
							<td width="152">
								<p>Support d'enregistrement</p>							</td>
							<td><select name="Support" size="1">
							  <option value="2">DVD</option>
							  <option value="3">CD DivX</option>
							  <option value="4">Classeurs</option>
							  <option value="1">VHS</option>
							  <option value="" selected>Tout</option>
								</select></td>
						</tr>
						<tr height="6">
							<td width="152" height="6"></td>
							<td height="6"></td>
						</tr>
						<tr>
							<td width="152"><input type="submit" name="submitButtonName"></td>
							<td><input type="reset"></td>
						</tr>
					</table>
			  </div>
			</form>
		  <p></p>
		</div>
		<div align="left">
			<p></p>
			<p></p>
		</div>
	</body>

</html>