<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Toutes les recettes</title>
		<link rel="icon" type="image/png" href="img/toque.ico" />
		<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="css/recette.css" media="screen" type="text/css" />
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

		<div id="ContenuPage">	

			<?php 
		
				//Recette 				
				$IDRecette=$laRecette->getIDRecette();
				$NomRecette=$laRecette->getNom_Recette();
				$Auteur=$laRecette->getAuteur_Recette();
				$Description=$laRecette->getDescription_Recette();
				$Photo=$laRecette->getAdresse_Photo();
				$adressePhoto = "img/recettes/".$Photo;
				$Temps_Min=$laRecette->getTemps_Preparation_Min();
				$Difficulte=$laRecette->getNiveau_Difficulte();
				
				//Auteur
				//print_r($createur);
				//$Pseudo=$createur["Pseudo"]; 
				$IDUtilisateur=$createur[0]->getIDUtilisateur();

				$createur=$createur[0]->getPseudo();
				
								
				//Ustensiles
				$Ustensiles=Ustensile::getUstensilesFromRecette($IDRecette);
				$nb=Ustensile::getNombreUstensilesFromRecette($IDRecette);
				$NombreUstensile=$nb[0];
				
				//Catégories 
				$Categories = Categorie::getCategoriesFromIDRecette($IDRecette);
				$nbCategorie=Categorie::getNombreCategorieFromRecette($IDRecette);
				$NombreCategorie=$nbCategorie[0];
				
				//Ingrédients
				$Ingredients = Ingredient::getIngredientsFromIDRecette($IDRecette);
				$nbIngredient = Ingredient::getNombreIngredientsFromRecette($IDRecette);
				$NombreIngredient=$nbIngredient[0];

				
				//Etapes
				$Etapes = Etape::getEtapesFromIDRecette($IDRecette);
				$nbEtape = Etape::getNombreEtapesFromIDRecette($IDRecette) ;
				$NombreEtape=$nbEtape[0];
			
				//Commentaires
				$Commentaires = Commentaire::getCommentairesFromIDRecette($IDRecette);
				$nbCommentaire = Commentaire::getNombreCommentaireFromIDRecette($IDRecette);
				$NombreCommentaire=$nbCommentaire[0];
				
				$Moyenne=Recette::getMoyenne($IDRecette);
			
			
			?>

		
				<!--Nom de la recette-->
				</br>
				<h2><center><?php echo "$NomRecette" ?></center></h2>
				
				<?php echo "<h3><center><i>ㅤㅤ$Description </i></center></h3></br>"; ?>
								
				<!--Image de la recette-->
				<img src='<?php echo "$adressePhoto" ?>'>
				
				<!--Difficulté et temps requis-->
				
				
				
				<div id=Side> 
					</br></br></br></br>
					<!--Createur-->
					• Créateur de la recette :
					<?php 
					echo "<a href='routeur.php?action=afficherProfil&IDUtilisateur=$IDUtilisateur' >$createur</a>";
					?>
					</br></br>
					
					<!--Moyenne-->
					• Moyenne des notes des utilisateurs :
					<?php
						for ($j=0;$j<$Moyenne;$j++){
							echo "★ ";
						}
						for ($j=$Moyenne;$j<10;$j++){
							echo "☆ ";
						}
					?>
				
					</br></br>
					• Temps de préparation : <?php echo "$Temps_Min" ?> minutesㅤㅤㅤ
					• Difficulé : <?php $Difficulte++ ;  echo "$Difficulte"?> /10
					</br></br>
					
					<?php
						for ($j=0;$j<$NombreCategorie;$j++){
							$CategoriePrincipale=$Categories[$j]->getType_Categorie();
							$Categorie_Secondaire=$Categories[$j]->getType_Secondaire();
							$Saveur=$Categories[$j]->getSaveur();
							echo "• Type de plat : $CategoriePrincipale";
							echo "ㅤ• Régime : $Categorie_Secondaire";
							echo "ㅤ• Saveur : $Saveur</br> ";
							if (!($j==$NombreCategorie-1)){
								echo ", ";
							}
						}
					?>
					<!--Description de la recette 
					• Description : </br> <?php// echo "ㅤㅤ$Description"; ?> -->
					</br></br></br>
					
				</div>
					
				<div id=Listes> 	
					</br></br>
					<!--Ustensiles nécessaires -->
					• Liste des ustensiles nécessaires :
					<?php
						for ($i=0;$i<$NombreUstensile;$i++){
							$Nom_Ustensile=$Ustensiles[$i]->getNom_Ustensile();
							echo "$Nom_Ustensile";
							if (!($i==$NombreUstensile-1)){
								echo ", ";
							}
						}
					?>
					</br></br></br>
					
					<!--Ingrédients-->
					• Ingrédients de la recette :
					<?php
						for ($j=0;$j<$NombreIngredient;$j++){
							$NomIngredient=$Ingredients[$j]->getNom_Ingredient();
							$QuantiteIngredient=$Ingredients[$j]->getQuantite_Ingredient();
							echo "$NomIngredient (x $QuantiteIngredient)";
							if (!($j==$NombreIngredient-1)){
								echo ", ";
							}
						}
					?>
					
					</br></br></br>

				</div>
				</div>
		</div>

		<div id="ContenuPage">	
				<div id=Etape> 
					</br>
					• Etapes à suivre :
					<?php
					for ($k=0;$k<$NombreEtape;$k){
						$Desc=$Etapes[$k]->getDescription_Etape();
						$k++;
						echo "</br> 
						Etape $k :</br>
						ㅤㅤㅤ$Desc </br>";	
					}
					?>
					</br>
				</div>
		</div>
		
		
		
		<div id="Commentaire">	
			<?php if(isset($_SESSION['IDUtilisateur']))  {?>
				<div class="FormulaireCommentaire">
					<p>Ajout d'un commentaire</p>
					<form action="routeur.php?action=EcrireCommentaire" method="post" autocomplete="off">
						<input type="hidden" name="action" value="EcrireCommentaire">
							<p>
								<label>• Contenu :</label>ㅤㅤㅤㅤㅤㅤㅤ
								<input type="text" name='Commentaire' autocomplete="off" size="90%" required>
							</p>
							<p>
								<label>• Note de la recette :</label>
								<select type="number" name="NoteCommentaire" autocomplete="off" required>
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
								</select>
							</p>
							<?php
								echo "<input type='hidden' name='IDUtilisateur' value='".$_SESSION['IDUtilisateur']."'>";
								echo "<input type='hidden' name='IDRecette' value='".$IDRecette."'>"; 
							?>
							<p>
								<input type="submit" value="Envoyer" name="envoyer"/>
							</p>
					</form>
				</div>
			<?php	}?>
				
		
			<div class="AfficherCommentaires">
				<!--Affichage des commentaires-->
				<?php
					if ($NombreCommentaire==0) {
						echo "<center>Aucun commentaire pour l'instant</center></br></br>";
					}
					
					for ($i=0;$i<$NombreCommentaire;$i++){
						echo "<div id='UnCommentaire'>";
							$IDCommentaire=$Commentaires[$i]->getIDCommentaire();
							$AuteurCommentaire=$Commentaires[$i]->getAuteurCommentaire($IDCommentaire);
							$Auteur=$AuteurCommentaire->getPseudo(); 
							$NomPhoto=$AuteurCommentaire->getAdresse_Photo_Profil(); if($NomPhoto==NULL){$NomPhoto="IconeUtilisateur.png";}
							$ContenuCommentaire=$Commentaires[$i]->getContenu();
							
							$dateCommentaire=$Commentaires[$i]->getDate_Commentaire();
							$noteCommentaire=$Commentaires[$i]->getNote_Commentaire();
							
							echo "$Auteur, le $dateCommentaire : " ;
							
							for ($j=0;$j<$noteCommentaire;$j++){
								echo "★ ";
							}
							for ($j=$noteCommentaire;$j<10;$j++){
								echo "☆ ";
							}
							
							echo "</br>";
							echo "<img src='img/utilisateurs/".$NomPhoto."' alt='IconeUtilisateur' id='imgUtilisateur'  width='75' height='75'>";
							echo "<p>« $ContenuCommentaire »</p>";
							echo "</br>";
						echo "</div>";
					}
				?>
			</div>
		</div>
	</body>
</html>
