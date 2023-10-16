<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Recette{
		private $IDRecette;
		private $Nom_Recette;
		private $Auteur_Recette;
		private $Description_Recette;
		private $Adresse_Photo;
		private $Temps_Preparation_Min;
		private $Niveau_Difficulte;
		private $IDAuteur;
		
		//Constructeur
		public function __construct($IdR=NULL,$NomR=NULL,$AuteurR=NULL,$DecR=NULL,$AdrPhoto=NULL,$TempsPrepaMin=NULL,$NvDifficulte=NULL,$IDA=NULL){
			if(!is_null($IdR)){
				$this->IDRecette = $IdR;
				$this->Nom_Recette = $NomR;
				$this->Auteur_Recette = $AuteurR;
				$this->Description_Recette = $DecR;
				$this->Adresse_Photo= $AdrPhoto;
				$this->Temps_Preparation_Min= $TempsPrepaMin;
				$this->Niveau_Difficulte=$NvDifficulte;
				$this->IDAuteur=$IDA;
			}
		}
		
		//Getter
		public function getIDRecette() {
			return $this->IDRecette;
		}
		public function getNom_Recette() {
			return $this->Nom_Recette;
		}
		public function getAuteur_Recette() {
			return $this->Auteur_Recette;
		}
		public function getDescription_Recette() {
			return $this->Description_Recette;
		}
		public function getAdresse_Photo() {
			return $this->Adresse_Photo;
		}
		public function getTemps_Preparation_Min() {
			return $this->Temps_Preparation_Min;
		}
		public function getNiveau_Difficulte() {
			return $this->Niveau_Difficulte;
		}
		public function getIDAuteur() {
			return $this->IDAuteur;
		}
		//Setter
		public function setIDRecette($IdR){
			$this->IDRecette=$IdR;
		}
		public function setNom_Recette($NomR){
			$this->Nom_Recette=$NomR;
		}
		public function setAuteur_Recette($AuteurR){
			$this->Auteur_Recette=$AuteurR;
		}
		public function setDescription_Recette($DecR){
			$this->Description_Recette=$DecR;
		}
		public function setAdresse_Photo($AdrPhoto){
			$this->Adresse_Photo=$AdrPhoto;
		}
		public function setTemps_Preparation_Min($TempsPrepaMin){
			$this->Temps_Preparation_Min=$TempsPrepaMin;
		}
		public function setNiveau_Difficulte($NvDifficulte){
			$this->Niveau_Difficulte=$NvDifficulte;
		}
		public function setIDAuteur($IDAuteur){
			$this->IDAuteur=$IDAuteur;
		}
		//Methode
		public function afficher(){
			echo "<p>Recette $this->IDRecette de marque $this->Auteur_Recette et son nom $this->Nom_Recette</p>";
		}
		
		public static function getAllRecettes(){
			$requete = "SELECT * FROM Recette;";
			$reponse = Connexion::pdo()->query($requete);
			$reponse -> setFetchMode(PDO::FETCH_CLASS,'Recette');
			$tab = $reponse->fetchAll();
			return $tab;
		}
		
		public static function getRecetteByID($IDRecette) {
			$requetePreparee = "SELECT * FROM Recette WHERE IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Recette');
				$v = $req_prep->fetch();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public static function NombreRecette(){
            $requetePreparee = "SELECT COUNT(*)+1 FROM Recette;";
            $req_prep = Connexion::pdo()->prepare($requetePreparee);
            
            try {
                $req_prep->execute();
                $req_prep->setFetchMode(PDO::FETCH_NUM);
                $NbrRecette = $req_prep->fetch();
                if (!$NbrRecette) 
                    return false;
                return $NbrRecette;
            } catch (PDOException $e) {
                echo "erreur : ".$e->getMessage()."<br>";
            }
            return false;
        }
		
		public static function addRecette($IDAuteur,$nomRecette,$Description,$Auteur,$Adresse_Photo,$Temps_Preparation_Min,$Niveau_Difficulte) {
			$requetePreparee = "INSERT INTO Recette VALUES (:tag_IDRecette,:tag_nomRecette,:tag_Auteur,:tag_Description,:tag_Adresse_Photo,:tag_Temps_Preparation_Min,:tag_Niveau_Difficulte,:tag_IDAuteur)";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);

			
			$valeurs = array(
				"tag_nomRecette" => $nomRecette,
				"tag_Description" => $Description,
				"tag_Auteur" => $Auteur,
				"tag_Adresse_Photo" => $Adresse_Photo,
				"tag_Temps_Preparation_Min" => $Temps_Preparation_Min,
				"tag_Niveau_Difficulte" => $Niveau_Difficulte,
				"tag_IDAuteur" => $IDAuteur,
			);
			
			$IdRecette = Recette::NombreRecette();
            $valeurs["tag_IDRecette"] = $IdRecette[0];			

			//print_r($valeurs);
			try {
				$req_prep->execute($valeurs);

			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
		}
		
				
		public static function updatePhotoRecette($NomPhoto){
			$requetePreparee = "UPDATE Recette SET Adresse_Photo=:tag_NomPhoto WHERE IDRecette=:tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_NomPhoto" =>$NomPhoto);
			
			//Récupère l'ID
			$IdU = Recette::NombreRecette();
			$IdU[0] = $IdU[0]-1; 
			$valeurs["tag_IDRecette"] = $IdU[0];
			
			try {
				$req_prep->execute($valeurs);
				return true;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
				return false;
			}
		}
		
		public static function getMoyenne($IDRecette){
			$Commentaires = Commentaire::getCommentairesFromIDRecette($IDRecette);
			$nbCommentaire = Commentaire::getNombreCommentaireFromIDRecette($IDRecette);
			$NombreCommentaire=$nbCommentaire[0];
				
			//Moyenne de la recette
			$Somme=0;
			for ($i=0;$i<$NombreCommentaire;$i++){
				$IDCommentaire=$Commentaires[$i]->getIDCommentaire();
				$noteCommentaire=$Commentaires[$i]->getNote_Commentaire();
				$Somme=$Somme+$noteCommentaire;
			}
			
			if ($NombreCommentaire==0) {
				return 0;
			}
				
			$Moyenne=$Somme/$NombreCommentaire;
			return $Moyenne;
		}
	
		public static function BarreDeRecherche($Demande){
			//$requetePreparee = "SELECT * FROM Recette R WHERE R.Nom_Recette LIKE '%".$Demande."%'";
			$requetePreparee = "SELECT DISTINCT R.IDRecette FROM Recette R INNER JOIN IngredientRecette IR ON R.IDRecette=IR.IDRecette INNER JOIN Ingredient I ON IR.IDIngredient=I.IDIngredient INNER JOIN CategorieRecette CR ON R.IDRecette=CR.IDRecette INNER JOIN Categorie C ON CR.IDCategorie=C.IDCategorie WHERE R.Nom_Recette LIKE '%".$Demande."%' OR I.Nom_Ingredient LIKE '%".$Demande."%' OR C.Type_Categorie LIKE '%".$Demande."%' OR C.Type_Secondaire LIKE '%".$Demande."%' OR C.Saveur LIKE '%".$Demande."%';";
			//return $requetePreparee;
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_Demande" => $Demande);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Recette');
				$v = $req_prep->fetchAll();
				
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		
		
        public static function getAuteurRecetteByID($IDRecette) {
            $requetePreparee = "SELECT U.IDUtilisateur,U.Pseudo FROM Utilisateur U INNER JOIN Recette R ON U.IDUtilisateur=R.IDAuteur WHERE R.IDRecette = :tag_IDRecette;";
            $req_prep = Connexion::pdo()->prepare($requetePreparee);
            $valeurs = array("tag_IDRecette" => $IDRecette);
            try {
                $req_prep->execute($valeurs);
                $req_prep->setFetchMode(PDO::FETCH_CLASS,'Utilisateur');
                $v = $req_prep->fetchAll();
                if (!$v) 
                    return false;
                return $v;
            } catch (PDOException $e) {
                echo "erreur : ".$e->getMessage()."<br>";
            }
            return false;
        }
		
		public static function ListeRecetteUtilisateur($IDUtilisateur) {
			$requetePreparee = "SELECT * FROM Recette R INNER JOIN UtilisateurRecette UR ON R.IDRecette=UR.IDRecette INNER JOIN Utilisateur U ON UR.IDUtilisateur=U.IDUtilisateur WHERE U.IDUtilisateur=:tag_ID;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_ID" => $IDUtilisateur);
			
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Recette');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
	}

?>