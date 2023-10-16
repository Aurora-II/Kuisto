<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Ustensile{
		private $IDstensile;
		private $Nom_Ustensile;
		private $Quantite_Ustensile;
		
		public function getIDstensile(){
			return $this->IDstensile;
		}

		public function setIDstensile($IDstensile){
			$this->IDstensile = $IDstensile;
		}

		public function getNom_Ustensile(){
			return $this->Nom_Ustensile;
		}

		public function setNom_Ustensile($Nom_Ustensile){
			$this->Nom_Ustensile = $Nom_Ustensile;
		}

		public function getQuantite_Ustensile(){
			return $this->Quantite_Ustensile;
		}

		public function setQuantite_Ustensile($Quantite_Ustensile){
			$this->Quantite_Ustensile = $Quantite_Ustensile;
		}

		
		//Constructeur
		public function __construct($IdU=NULL,$NomU=NULL,$QU=NULL){
			if(!is_null($IdU)){
				$this->IDUstensile= $IdU;
				$this->Nom_Ustensile = $NomU;
				$this->Quantite_Ustensile= $QU;
			}
		}
		
		public static function getUstensilesFromRecette($IDRecette) {
			$requetePreparee = "SELECT Nom_Ustensile,Quantite_Ustensile FROM Ustensile U INNER JOIN UstensileRecette UR ON UR.IDUstensile=U.IDUstensile INNER JOIN Recette R ON R.IDRecette=UR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Ustensile');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public static function getNombreUstensilesFromRecette($IDRecette) {
			$requetePreparee = "SELECT COUNT(*) FROM Ustensile U INNER JOIN UstensileRecette UR ON UR.IDUstensile=U.IDUstensile INNER JOIN Recette R ON R.IDRecette=UR.IDRecette WHERE R.IDRecette = :tag_IDRecette;";
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
		
		public static function nouveauIDUstensile() {
			$requetePreparee = "SELECT COUNT(*) FROM Ustensile U;";
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
		
		public function obtientIDUstensile(){
			$requetePreparee = "SELECT IDUstensile FROM Ustensile ORDER BY IDUstensile desc limit 1";
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
		
		public function addUstensileToRecette($IDRecette,$IDUstensile,$Ustensile,$Qte){
			$requetePreparee = "INSERT INTO Ustensile VALUES(:tag_IDUstensile,:tag_Ustensile,:tag_Qte);INSERT INTO UstensileRecette VALUES(:tag_IDRecette,:tag_IDUstensile);";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_IDRecette" => $IDRecette,
				"tag_Ustensile" => $Ustensile,
				"tag_Qte" => $Qte,
				"tag_IDUstensile"=>$IDUstensile++,
			);
						
			try {
				$req_prep->execute($valeurs);
				
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function dropDernierUstensile(){
			$requetePreparee = "DELETE FROM UstensileRecette ORDER BY IDUstensile desc limit 1; DELETE FROM Ustensile ORDER BY IDUstensile desc limit 1; ";
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