<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Résultats</title>
		<link rel="icon" type="image/png" href="img/toque.ico" />
		<link rel="stylesheet" href="css/main.css" media="screen" type="text/css" />
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/> <!--Utile pour la barre de recherche-->
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
		
			<h3><center>Liste des recettes trouvées</center></h3>
			
			<center>
				<div class="search-box">
					<form action="routeur.php" method="get">
						<input type="hidden" name="action" value="chercherRecette">
						<button class="btn-search"><i class="fa fa-search"></i></button>
						<input type="text" name="search" id="search" class="input-search" placeholder="Ecrivez ici...">
					</form>
				</div>
			</center>
	
			<?php				
				
				/*
				print_r($Demande);
				echo "</br>";
				print_r($RecetteRecherche);
				echo "</br>";
				*/
				
				if (!($RecetteRecherche==NULL)){
			
					foreach ($RecetteRecherche as $recette2){
						$recette3=$recette2->getIDRecette();
						//print_r($recette3);
						
						$recette=Recette::getRecetteByID($recette3);
						
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
				}
				else {
					echo "</br><center>Aucune recette trouvée</center></br>";
				}
				
			?>
		</div>

	</body>
</html>