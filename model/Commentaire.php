<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Commentaire{
		private $IDCommentaire;
		private $Contenu;
		private $Note_Commentaire;
		private $Date_Commentaire;
		private $IDUtilisateur_Commentaire;
		private $IDRecette_Commentaire;
		
		//Constructeur
		public function __construct($IDC=NULL,$C=NULL,$NC=NULL,$DC=NULL,$IUC=NULL,$IDRC=NULL){
			if(!is_null($IDC)){
				$this->IDCommentaire = $IdC;
				$this->Contenu = $C;
				$this->Note_Commentaire = $NC;
				$this->Date_Commentaire = $DC;
				$this->IDUtilisateur_Commentaire = $IUC;
				$this->IDRecette_Commentaire = $IDRC;
			}
		}
		
		//GETTER
		public function getIDCommentaire(){
			return $this->IDCommentaire;
		}
		public function getContenu(){
			return $this->Contenu;
		}
		public function getNote_Commentaire(){
			return $this->Note_Commentaire;
		}
		public function getDate_Commentaire(){
			return $this->Date_Commentaire;
		}
		public function getIDUtilisateur_Commentaire(){
			return $this->IDUtilisateur_Commentaire;
		}
		public function getIDRecette_Commentaire(){
			return $this->IDRecette_Commentaire;
		}
		
		//SETTER
		public function setIDCommentaire($IDCommentaire){
			$this->IDCommentaire = $IDCommentaire;
		}
		public function setContenu($Contenu){
			$this->Contenu = $Contenu;
		}
		public function setNote_Commentaire($Note_Commentaire){
			$this->Note_Commentaire = $Note_Commentaire;
		}
		public function setDate_Commentaire($Date_Commentaire){
			$this->Date_Commentaire = $Date_Commentaire;
		}	
		public function setIDUtilisateur_Commentaire($IDUtilisateur_Commentaire){
			$this->IDUtilisateur_Commentaire = $IDUtilisateur_Commentaire;
		}
		public function setIDRecette_Commentaire($IDRecette_Commentaire){
			$this->IDRecette_Commentaire = $IDRecette_Commentaire;
		}
		
		//Methode
		public function addCommentaire($Contenu,$Note,$IDUtilisateur,$IDRecette){
			$requetePreparee = "INSERT INTO Commentaire VALUES(0,:tag_Contenu,:tag_Note,:tag_Date,:tag_ID_U,:tag_ID_R);";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_Contenu" =>$Contenu ,
				"tag_Note" =>$Note ,
				"tag_Date" =>date('Y/m/d'),
				"tag_ID_U" =>$IDUtilisateur ,
				"tag_ID_R" =>$IDRecette,
			);
			
			try{
				$req_prep->execute($valeurs);
				return true;
			} catch (PDOException $e){
				echo "erreur : ".$e->getMessage()."<br>";
				return false;
			}
		}
		
		
		public function getCommentairesFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT * FROM Commentaire C WHERE IDRecette_Commentaire = :tag_IDRecette;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDRecette" => $IDRecette);
			
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Commentaire');
				$v = $req_prep->fetchAll();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function getNombreCommentaireFromIDRecette($IDRecette) {
			$requetePreparee = "SELECT COUNT(*) FROM Commentaire C WHERE IDRecette_Commentaire = :tag_IDRecette;";
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
		
		public function getAuteurCommentaire($IDCommentaire) {
			$requetePreparee = "SELECT Pseudo,Addr_Photo_Profil FROM Utilisateur U INNER JOIN Commentaire C ON C.IDUtilisateur_Commentaire=U.IDUtilisateur WHERE C.IDCommentaire = :tag_IDCommentaire;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDCommentaire" => $IDCommentaire);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Utilisateur');
				$v = $req_prep->fetch();
				if (!$v) 
					return false;
				return $v;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
		
		public function getListeCommentaire($IDUtilisateur) {
			$requetePreparee = "SELECT * FROM Commentaire C INNER JOIN Utilisateur U ON C.IDUtilisateur_Commentaire=U.IDUtilisateur WHERE U.IDUtilisateur= :tag_IDUtilisateur;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_IDUtilisateur" => $IDUtilisateur);
			try {
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Commentaire');
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