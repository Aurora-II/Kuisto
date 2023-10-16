<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Etape{
		private $IDEtape;
		private $Description_Recette;
		private $IDRecette;
		
		//Constructeur
		public function __construct($IDE=NULL,$DESC=NULL,$IDR=NULL){
			if(!is_null($IDE)){
				$this->IDRecette = $IdR;
				$this->IDEtape = $IDE;
				$this->Decription_Recette = $DESC;
				$this->IDRecette = $IDR;
			}
		}
		
		//Getter & Set
		public function getIDEtape(){
			return $this->IDEtape;
		}

		public function setIDEtape($IDEtape){
			$this->IDEtape = $IDEtape;
		}

		public function getDescription_Etape(){
			return $this->Description_Etape;
		}

		public function setDescription_Etape($Description_Etape){
			$this->Description_Etape = $Description_Etape;
		}

		public function getIDRecette(){
			return $this->IDRecette;
		}

		public function setIDRecette($IDRecette){
			$this->IDRecette = $IDRecette;
		}
		
		//Methode
		public function getEtapesFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT Description_Etape FROM Etape WHERE IDRecette = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Etape');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function getNombreEtapesFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT COUNT(*) FROM Etape WHERE IDRecette = :tag_IDRecette;";
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
		
		public function addEtapeToRecette($IDRecette,$DescriptionEtape){
			$requetePreparee = "INSERT INTO Etape VALUES(0,:tag_Desc,:tag_IDRecette)";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_IDRecette" => $IDRecette,
				"tag_Desc" => $DescriptionEtape
			);
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
		
		public function dropDerniereEtape(){
			$requetePreparee = "DELETE FROM Etape ORDER BY IDEtape desc limit 1";
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