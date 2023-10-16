<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Ingredient{
		private $IDIngredient;
		private $Nom_Ingredient;
		private $Quantite_Ingredient;
		
		//Getter & Set
		public function getIDIngredient(){
			return $this->IDIngredient;
		}

		public function setIDIngredient($IDIngredient){
			$this->IDIngredient = $IDIngredient;
		}

		public function getNom_Ingredient(){
			return $this->Nom_Ingredient;
		}

		public function setNom_Ingredient($Nom_Ingredient){
			$this->Nom_Ingredient = $Nom_Ingredient;
		}

		public function getQuantite_Ingredient(){
			return $this->Quantité_Ingredient;
		}

		public function setQuantite_Ingredient($Quantite_Ingredient){
			$this->Quantité_Ingredient = $Quantite_Ingredient;
		}
		
		//Methode
		public function getIngredientsFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT * FROM Ingredient I INNER JOIN IngredientRecette IR ON IR.IDIngredient=I.IDIngredient INNER JOIN Recette R ON R.IDRecette=IR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Ingredient');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public static function getNombreIngredientsFromRecette($IDRecette) {
			$requetePreparee = "SELECT COUNT(*) FROM Ingredient I INNER JOIN IngredientRecette IR ON IR.IDIngredient=I.IDIngredient INNER JOIN Recette R ON R.IDRecette=IR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
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
		
		public static function nouveauIDIngredient() {
			$requetePreparee = "SELECT COUNT(*) FROM Ingredient I;";
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
		
		public function obtientIDIngredient(){
			$requetePreparee = "SELECT IDIngredient FROM Ingredient ORDER BY IDIngredient desc limit 1";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);

			try {
				$req_prep->execute();
				$req_prep->setFetchMode(PDO::FETCH_NUM);
				$v = $req_prep->fetch();
				$v++;
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function addIngredientToRecette($IDRecette,$IDIngredient,$Ingredient,$Qte){
			$requetePreparee = "INSERT INTO Ingredient VALUES(:tag_IDIngredient,:tag_Ingredient,:tag_Qte);INSERT INTO IngredientRecette VALUES(:tag_IDRecette,:tag_IDIngredient);";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_IDRecette" => $IDRecette,
				"tag_Ingredient" => $Ingredient,
				"tag_Qte" => $Qte,
				"tag_IDIngredient" => $IDIngredient++,
			);
			
			
			try {
				$req_prep->execute($valeurs);
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function dropDernierIngredient(){
			$requetePreparee = "DELETE FROM IngredientRecette ORDER BY IDIngredient desc limit 1; DELETE FROM Ingredient ORDER BY IDIngredient desc limit 1; ";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);

			try {
				$req_prep->execute();
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
	}
?>