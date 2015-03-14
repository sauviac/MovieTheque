<?php 
require_once("./Fonc_BASE.php");
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

	<?php
		include('entete.html');
	?>

	<body bgcolor="#ffffff">

		<?php
		include('navigationConsult.html');
		?>
	
		<div class="container-fluid">


		
			
		<form class="form-horizontal" action="MovieConsult.php" method="get" name="Selection" enctype="multipart/form-data">
		
		  			<input type="hidden" name="Origine" value="Selection">
					 <div class="form-group">
						<label for="Titre" class="col-sm-2 control-label">Titre ou mot du titre</label>
						<div class="col-sm-10">
						<input type="text" class="form-control"  name="Titre" id="Titre" placeholder="Entrez Titre">
						</div>
					</div>					
					<div class="form-group">
						<label for="Realisateur" class="col-sm-2 control-label">R&eacute;alisateur</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="Realisateur" id="Realisateur" placeholder="Entrez Realisateur">
						</div>
					</div>
					<div class="form-group">
						<label for="Annee" class="col-sm-2 control-label">Ann&eacute;e</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="Annee" id="Annee" placeholder="Entrez Annee de sortie">
						</div>
					</div>
					<div class="form-group">
						<label for="Genre" class="col-sm-2 control-label">Genre</label>
						<div class="col-sm-10">
						<p><select name="Genre" id="Genre" size="1" class="form-control" >
							<option value="" selected>Tout</option>
							<?php echo $liste_des_genres ;?>
							</select>
						</p>
						</div>
					</div>
					<div class="form-group">
						<label for="Annee" class="col-sm-2 control-label">Ann&eacute;e</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="Annee" id="Annee" placeholder="Entrez Annee de sortie">
						</div>
					</div>
					<div class="form-group">
						<label for="Duree" class="col-sm-2 control-label">Dur&eacute;e MAX (en minutes)</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="Duree" id="Duree" placeholder="Entrez Duree de sortie">
						</div>
					</div>
					<div class="form-group">
						<label for="Title" class="col-sm-2 control-label">Titre original</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" name="Title" id="Title" placeholder="Entrez Titre original">
						</div>
					</div>
					<div class="form-group">
						<label for="Support" class="col-sm-2 control-label">Support d'enregistrement</label>
						<div class="col-sm-10">
						<p><select name="Support" size="1" class="form-control" >
							  <option value="2">DVD</option>
							  <option value="3">CD DivX</option>
							  <option value="4">Classeurs</option>
							  <option value="1">VHS</option>
							  <option value="" selected>Tout</option>
							</select>
						</p>
						</div>
					</div>
					<div class="form-group">	
						<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary">Envoyer</button>
						<button type="reset" class="btn btn-warning">Annuler</button>
						</div>
					</div>

			</form>
		  <p></p>
		
	</div>
	</body>

</html>