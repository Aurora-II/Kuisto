<?php
	require_once("model/Recette.php");
	require_once("model/Ustensile.php");
	require_once("model/Etape.php");
	require_once("model/Categorie.php");
	require_once("model/Utilisateur.php");
	require_once("model/Commentaire.php");
	require_once("model/Ingredient.php");
	require_once("conf/Connexion.php");
	Connexion::connect();

	class controllerGeneral{
		
		public static function readAll(){ //Methode inutile c'était une démo
			session_start();
			$lesRecettes=Recette::getAllRecettes();
			include("view/accueil.php");		
		}

		public static function afficherRecette() {
			session_start();
			$IDRecette=$_GET['IDRecette'];
			$laRecette=Recette::getRecetteByID($IDRecette);
			$createur=Recette::getAuteurRecetteByID($IDRecette);
			include("view/detailsRecette.php");
		}
			
		public static function createAccount(){ //Créée un Utilisateur
			include("view/createAccount.php");
		}
		
		public static function createdAccount(){ 
			$M = htmlspecialchars($_POST["Mail"]);
			$P = htmlspecialchars($_POST["Pseudo"]);
			$Mdp = htmlspecialchars($_POST["Mdp"]);
			$DecU = htmlspecialchars($_POST["DecU"]);
			$adressPhotoProfil = htmlspecialchars($_FILES['NomPhoto']['name']);
			Utilisateur::addUtilisateur($P,$DecU,$Mdp,$M,$adressPhotoProfil);
			
			if(isset($_POST['envoyer'])){
				$img = $_FILES['NomPhoto']['name'];
				$img_loc=$_FILES['NomPhoto']['tmp_name'];
				$img_folder="img/utilisateurs/";
			if (move_uploaded_file($img_loc,$img_folder.$img)){
				echo "Réussi !!";
			} else {
				echo "Echoué !!";
				}	
			}
			//self::connect();
		}
					
		public static function connect(){ //Permet à un Utilisateur de se connecter
			include("view/connect.php");
		}
		
		public static function connected(){
			
			if(isset($_POST['Pseudo']) && isset($_POST['Mdp'])) {
				
				// Connexion à la base de données
				$db = mysqli_connect('localhost', 'gsantos', 'vC4Jdx94dWTSw8rz','gsantos') or die('could not connect to database');
				
				// Les deux fonctions mysqli_real_escape_string et htmlspecialchars empêchent les attaques de type injection SQL et XSS
				$username = mysqli_real_escape_string($db,htmlspecialchars($_POST['Pseudo'])); 
				$password = mysqli_real_escape_string($db,htmlspecialchars($_POST['Mdp']));
				
				if($username !== "" && $password !== "") {
					$TabID=Utilisateur::ObtenirIDUtilisateur($username,$password);

					if($TabID["IDUtilisateur"]!=NULL) // Pseudo et mot de passe correctes
					{
						session_start();
						$_SESSION['Pseudo'] = $username;
						$_SESSION['Mdp'] = $password;
						
						//Récupération des informations de l'utilisateur
						$TabInfoUtilisateur = Utilisateur::InfoUtilisateur($TabID["IDUtilisateur"]);
						$_SESSION["IDUtilisateur"]=$TabID["IDUtilisateur"];
						$Statut=$TabInfoUtilisateur->getStatut(); $_SESSION["Statut"]=$Statut;
						$NomImage=$TabInfoUtilisateur->getAdresse_Photo_Profil();$_SESSION["NomPhoto"]=$NomImage;
						if($NomImage=="") 
							$_SESSION["NomPhoto"]="IconeUtilisateur.png";
						
						$lesRecettes=Recette::getAllRecettes();
						include("view/accueil.php");
					}
					else
					{
					   header('Location: routeur.php?action=connect&erreur=1'); // Pseudo ou mot de passe incorrect
					}
				}
			}
			else
			{
			   header('Location: routeur.php?action=connect');
			}
			mysqli_close($db); // fermer la connexion
		}
		
		public static function Deconnecte(){
			session_start();
			session_unset();
			session_destroy();
			setcookie(session_name,"",time()-1);

			$lesRecettes=Recette::getAllRecettes();
			include("view/accueil.php");
		}
		
		//Ajout une recette
		public static function createRecette() {
			session_start();
			include("view/ajouterRecette.php");
		}
		
		public static function createdRecette() {
			//Table Recette
			$nomRecette = htmlspecialchars($_POST["nomRecette"]);			
			$Description = htmlspecialchars($_POST["Description"]);		
			$Adresse_Photo = htmlspecialchars($_FILES['NomPhoto']['name']);
			$Adresse_Photo=str_replace(" ","_",$Adresse_Photo);			
			$tempsMinutes = htmlspecialchars($_POST["tempsMinutes"]); 	
			$Difficulte = htmlspecialchars($_POST["Difficulte"]);			
			//Table Categorie
			$categorie1 = htmlspecialchars($_POST["typePlat"]);
			$categorie2= htmlspecialchars($_POST["regime"]);		
			$saveur = htmlspecialchars($_POST["saveur"]);
			//Table Utilisateur
			$Auteur = htmlspecialchars($_POST["Auteur"]);		
			$IDAuteur = htmlspecialchars($_POST["IDAuteur"]);
			
			//ID de la recette
			$NouveauID=Recette::NombreRecette();
			$NouveauID= $NouveauID[0];
			
			//Ajout de la Recette dans la table
			$NouvelleRecette=Recette::addRecette($IDAuteur,$nomRecette,$Description,$Auteur,$Adresse_Photo,$tempsMinutes,$Difficulte);

			//Catégories
			$NouveauIDCategorie=Categorie::getNouveauIDCategorie();
			$NouveauIDCategorie++;

			Categorie::addCategorieToRecette($NouveauID,$NouveauIDCategorie[0],$categorie1,$categorie2,$saveur);
			
			//Ingrédients
			$IdIngredient = Ingredient::obtientIDIngredient();
			$IdIngredient=$IdIngredient[0];
			$IdIngredient++;
			
			foreach($_POST['ingredient'] as $ingredient) {
				
					$ingredient= htmlspecialchars($ingredient);
					
					//Récupère la quantité nécessaire pour un ingrédient
					$qte=$_POST['ingredientQte'];
					$qte=$qte[0];
					$qte= htmlspecialchars($qte);
					
					//Ajoute l'ingrédient à la BD
					Ingredient::addIngredientToRecette($NouveauID,$IdIngredient,$ingredient,$qte);
					$IdIngredient=$IdIngredient+1;
		
			}
				Ingredient::dropDernierIngredient();
				
				
			//Ustensiles
			$IdUstensile = Ustensile::nouveauIDUstensile();
			$IdUstensile=$IdUstensile[0];
			
			foreach($_POST['ustensiles'] as $ustensile) {
				
					$ustensile= htmlspecialchars($ustensile);
					
					//Récupère la quantité nécessaire pour un ustensile
					$qte=$_POST['ustensilesQte'];
					$qte=$qte[0];
					$qte= htmlspecialchars($qte);
					
					//Ajoute l'ustensile à la BD
					Ustensile::addUstensileToRecette($NouveauID,$IdUstensile,$ustensile,$qte);
					$IdUstensile=$IdUstensile+1;
		
			}
				Ustensile::dropDernierUstensile();
				
			
			//Etapes
			foreach($_POST['etape'] as $etape) {
				$etape= htmlspecialchars($etape);
				Etape::addEtapeToRecette($NouveauID,$etape);
			}
				Etape::dropDerniereEtape();
				
			//Enregistrement de l'image
			if(isset($_POST['Envoyer'])){
				$img = $_FILES['NomPhoto']['name'];
				$img_loc=$_FILES['NomPhoto']['tmp_name'];
				$img_folder="img/recettes/";
				$img=str_replace(" ","_",$img);
			if (move_uploaded_file($img_loc,$img_folder.$img)){
				echo "Réussi !!";
			} else {
				echo "Echoué !!";
				}	
			}
			
			session_start();
            $lesRecettes=Recette::getAllRecettes();
            include("view/accueil.php");
		}
		
		public static function chercherRecette(){
			$Demande=$_GET["search"];
			
			if ($Demande==NULL){
				session_start();
				$lesRecettes=Recette::getAllRecettes();
				include("view/accueil.php");
			}
			else {
				session_start();
				$RecetteRecherche=Recette::BarreDeRecherche($Demande);
				include("view/resultatRecherche.php");
			}
		}
		
		public static function afficherProfil() {
			session_start();
			$IDUtilisateur=$_GET['IDUtilisateur']; //On récupère ID de l'Utilisateur
			
			//On prend toutes les informations concernant l'utilisateur
			$TabUtilisateur=Utilisateur::InfoUtilisateur($IDUtilisateur);
			$TabRecette=Recette::ListeRecetteUtilisateur($IDUtilisateur);
			$TabCommentaire=Commentaire::getListeCommentaire($IDUtilisateur);
			
			include("view/Profil.php");
		}
		
		public static function EcrireCommentaire(){
			$Contenu=htmlspecialchars($_POST['Commentaire']);
			$Note=htmlspecialchars($_POST['NoteCommentaire']);
			$IDUtilisateur=htmlspecialchars($_POST['IDUtilisateur']);
			$IDRecette=htmlspecialchars($_POST['IDRecette']);
			
			session_start();
			Commentaire::addCommentaire($Contenu,$Note,$IDUtilisateur,$IDRecette);
			$laRecette=Recette::getRecetteByID($IDRecette);
			$createur=Recette::getAuteurRecetteByID($IDRecette);
			header('Location: routeur.php?action=afficherRecette&IDRecette='.$IDRecette);
		}
	}
?>