<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profil</title>
		<link rel="icon" type="image/png" href="img/toque.ico" />
		<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="css/utilisateur.css" media="screen" type="text/css" />
	</head>
	<body>
		<div class="Bandeau">
		
			<!--Logo du site-->
			<div class="Logo">
				<img src="img/Logo.png" alt="Logo">
			</div>
			
			<?php		
				//Si l'utilisateur est connecté, on affiche la photo de profil et des fonctionnalités en plus
				if(isset($_SESSION['IDUtilisateur']))  {  
			?>						
					<!--Elements du bandeau relatif à l'utilisateur-->
					
					<div id='Utilisateur'>
					
						<?php 
							echo "<a href='routeur.php?action=afficherProfil&IDUtilisateur=".$_SESSION['IDUtilisateur']."'>
							<img src='img/utilisateurs/".$_SESSION["NomPhoto"]."' alt='IconeUtilisateur' id='imgUtilisateur'  width='75' height='75'>
							</a>"; 
						?>	
						
						</br>
						
						<!--Bouton Déconnexion-->
						<form action='routeur.php' method='post'>
							<input type='hidden' name='action' value='Deconnecte'>
							<input type='submit' name='envoyer' value='Déconnexion'>
						</form>
						
					</div>
					
					<!--Elements du bandeau lorsque l'on est connecté-->
					<div id="Selection">
						<a href='routeur.php?action=readAll'>Accueil</a>
						<a href='routeur.php?action=chercherRecette&search=Entrée'>Entrées</a>
						<a href='routeur.php?action=chercherRecette&search=Plat'>Plats</a>
						<a href='routeur.php?action=chercherRecette&search=Dessert'>Desserts</a>
						<a href='routeur.php?action=chercherRecette&search=Autre'>Autre</a>
						<a href='routeur.php?action=createRecette'>Ajouter Recette</a>
					</div>
					
					<?php	} 
					else { 
					?>	
					
					<!--Bouton Connexion-->
					<div id='Utilisateur'>
						<form action='routeur.php' method='get'>
							<input type='hidden' name='action' value='connect'>
							<input type='submit' name='envoyer' value='Connexion'>
						</form>
					</div>
					
					<div id="Selection">
						<a href='routeur.php?action=readAll'>Accueil</a>
						<a href='routeur.php?action=chercherRecette&search=Entrée'>Entrées</a>
						<a href='routeur.php?action=chercherRecette&search=Plat'>Plats</a>
						<a href='routeur.php?action=chercherRecette&search=Dessert'>Desserts</a>
						<a href='routeur.php?action=chercherRecette&search=Autre'>Autre</a>
					</div>
			<?php	} ?>
			
		</div>

		<?php
			//Les informations de la table Utilisateur
				$ID=$TabUtilisateur->getIDUtilisateur();
				$Statut=$TabUtilisateur->getStatut();
				$Pseudo=$TabUtilisateur->getPseudo();
				$Description=$TabUtilisateur->getDescription_Utilisateur();
				$Mail=$TabUtilisateur->getMail();
				$NomImage=$TabUtilisateur->getAdresse_Photo_Profil();
				if ($NomImage==NULL or $NomImage==""){
					$NomImage="IconeUtilisateur.png";
				}
				$adressePhoto = "img/utilisateurs/".$NomImage;
		?>


		<div id="ContenuPage">	
					<!--Nom de l'utilisateur-->
					
			<div id="imgUtilisateur">
				<img src='<?php echo "$adressePhoto" ?>' width="250px" height="250px">
			</div>
					
			<h2><?php echo "$Pseudo" ?></h2>
				
				
			<?php 
						
				if ($Description=="" OR $Description==NULL){
					echo "<h3>Aucune description disponible</h3>";
				}
				else {
					echo "<h3>Description : $Description </h3>";
				}
				echo "<h3>Liste des recettes de $Pseudo :</h3>";
			?>

			
		<div id="listeRecette">	
		<?php
			if ($TabRecette!=""){
				foreach ($TabRecette as $recette){
					$NomRecette = $recette->getNom_Recette();
					$NomImage = $recette->getAdresse_Photo();
					$IDRecette = $recette->getIDRecette();
					
					echo "<div class=BlockRecette>";
						echo "<center>";
							echo "<b>".$NomRecette."</b>";
							echo "<a href='routeur.php?action=afficherRecette&IDRecette=$IDRecette' ><img src="."img/recettes/".$NomImage." width="."150px"."> </a>";
								echo "<div id=Note>";
									$Moyenne=Recette::getMoyenne($IDRecette);	
									
									for ($j=0;$j<$Moyenne;$j++){
										echo "★ ";
									}
								
									for ($j=$Moyenne;$j<10;$j++){
										echo "☆ ";
									}
								
								echo "</div>";
						echo "</center>";
					echo "</div>";
				}
			}else{
				echo "<center><p>Cet Utilisateur à aucune recette</p></center>";
			}
			
		?>
		</div>
	</div>
	
	<div id="ContenuPage">	
		<?php 

			if ($TabCommentaire!=""){	
				foreach ($TabCommentaire as $Commentaire){
					$IDCommentaire=$Commentaire->getIDCommentaire();
					$AuteurCommentaire=$Commentaire->getAuteurCommentaire($IDCommentaire);
					$Auteur=$AuteurCommentaire->getPseudo();
					
					$ContenuCommentaire=$Commentaire->getContenu();
					
					$dateCommentaire=$Commentaire->getDate_Commentaire();
					$noteCommentaire=$Commentaire->getNote_Commentaire();
					
					echo "</br>
					$Auteur, le $dateCommentaire :    " ;
					
					for ($j=0;$j<$noteCommentaire;$j++){
						echo "★ ";
					}
					for ($j=$noteCommentaire;$j<10;$j++){
						echo "☆ ";
					}
					
					echo "</br> 
					ㅤㅤㅤ« $ContenuCommentaire » </br>";
					echo "</br>";
				}
			}else {
				echo "<center><p>Cet Utilisateur à aucun commentaire</p></center>";
			}
		?>
	</div>
</div>