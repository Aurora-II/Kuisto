<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Categorie{
		private $IDCategorie;
		private $Type_Categorie;
		private $Type_Secondaire;
		private $Saveur;
		
		//Getter & Set
		function setIDCategorie($IDCategorie) { $this->IDCategorie = $IDCategorie; }
		function getIDCategorie() { return $this->IDCategorie; }
		function setType_Categorie($Type_Categorie) { $this->Type_Categorie = $Type_Categorie; }
		function getType_Categorie() { return $this->Type_Categorie; }
		function setType_Secondaire($Type_Secondaire) { $this->Type_Secondaire = $Type_Secondaire; }
		function getType_Secondaire() { return $this->Type_Secondaire; }
		function setSaveur($Saveur) { $this->Saveur = $Saveur; }
		function getSaveur() { return $this->Saveur; }

		//Constructeur
		public function __construct($IDC=NULL,$TC=NULL,$TS=NULL,$S=NULL){
			if(!is_null($IDC)){
				$this->IDCategorie = $IDC;
				$this->Type_Categorie = $TC;
				$this->Type_Secondaire = $TS;
				$this->Saveur = $S;
			}
		}
		
		//Methode
		public function getCategoriesFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT Type_Categorie,Type_Secondaire,Saveur FROM Categorie C INNER JOIN CategorieRecette CR ON CR.IDCategorie=C.IDCategorie INNER JOIN Recette R ON R.IDRecette=CR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Categorie');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public static function getNombreCategorieFromRecette($IDRecette) {
			$requetePreparee = "SELECT COUNT(*) FROM Categorie C INNER JOIN CategorieRecette CR ON CR.IDCategorie=C.IDCategorie INNER JOIN Recette R ON R.IDRecette=CR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_NUM);
				$v = $req_prep->fetch();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function addCategorieToRecette($IDRecette,$IDCategorie,$Type_Categorie,$Type_Secondaire,$Saveur){
			$requetePreparee = "INSERT INTO Categorie VALUES(:tag_IDCategorie,:tag_Type_Categorie,:tag_Type_Secondaire,:tag_Saveur); INSERT INTO CategorieRecette VALUES(:tag_IDCategorie,:tag_IDRecette);";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_IDRecette" => $IDRecette,
				"tag_IDCategorie" => $IDCategorie,
				"tag_Type_Categorie" => $Type_Categorie,
				"tag_Type_Secondaire" => $Type_Secondaire,
				"tag_Saveur" => $Saveur,
			);
			
            $IDCategorie++;
			$valeurs["tag_IDCategorie"] = $IDCategorie;
			//print_r($IDCategorie);
			
			try {
				$req_prep->execute($valeurs);
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public static function getNouveauIDCategorie() {
			$requetePreparee = "SELECT COUNT(*) FROM Categorie C";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);

			try {
				$req_prep->execute();
				$req_prep->setFetchMode(PDO::FETCH_NUM);
				$v = $req_prep->fetch();
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