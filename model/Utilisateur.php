<?php
	require_once("conf/Connexion.php");
	Connexion::connect();
		
	class Utilisateur{
		private $IDUtilisateur;
		private $Statut;
		private $Pseudo;
		private $Description_Utilisateur;
		private $Mot_de_passe;
		private $Mail;
		private $Addr_Photo_Profil;
		
		//Constructeur
		public function __construct($IdU=NULL,$S=NULL,$P=NULL,$DecU=NULL,$Mdp=NULL,$M=NULL,$adressPhotoProfil=NULL){
			if(!is_null($IdU)){
				$this->IDUtilisateur = $IdU;
				$this->Statut = $S;
				$this->Pseudo= $P;
				$this->Description_Utilisateur = $DecU;
				$this->Mot_de_passe = $Mdp;
				$this->Mail= $M;
				$this->Addr_Photo_Profil= $adressPhotoProfil;
			}
		}
		
		//Getter
		public function getIDUtilisateur() {
			return $this->IDUtilisateur;
		}
		public function getStatut() {
			return $this->Statut;
		}
		public function getPseudo() {
			return $this->Pseudo;
		}
		public function getDescription_Utilisateur() {
			return $this->Description_Utilisateur;
		}
		public function getMot_de_passe() {
			return $this->Mot_de_passe;
		}
		public function getMail() {
			return $this->Mail;
		}
		public function getAdresse_Photo_Profil() {
			return $this->Addr_Photo_Profil;
		}
		//Setter
		public function setIDUtilisateur($IdU){
			$this->IDUtilisateur=$IdU;
		}
		public function setStatut($S){
			$this->Statut=$S;
		}
		public function setPseudo($P){
			$this->Pseudo=$P;
		}
		public function setDescription_Utilisateur($DecU){
			$this->Description_Utilisateur=$DecU;
		}
		public function setMot_de_passe($Mdp){
			$this->Mot_de_passe=$Mdp;
		}
		public function setMail($M){
			$this->Mail=$M;
		}
		public function setAdresse_Photo_Profil($Photo_Profil){
			$this->Addr_Photo_Profil=$Photo_Profil;
		}
		
		//METHODES
		public static function NombreUtilisateur(){
			$requetePreparee = "SELECT COUNT(*) FROM Utilisateur;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			
			try {
				$req_prep->execute();
				$req_prep->setFetchMode(PDO::FETCH_NUM);
				$NbrUtilisateur = $req_prep->fetch();
				if (!$NbrUtilisateur) 
					return false;
				return $NbrUtilisateur;
			} catch (PDOException $e) {
				echo "erreur : ".$e->getMessage()."<br>";
			}
			return false;
		}
			
		public static function addUtilisateur($P,$DecU,$Mdp,$M,$adressPhotoProfil){
			$requetePreparee = "INSERT INTO Utilisateur VALUES(:tag_IDUtilisateur, :tag_Statut, :tag_Pseudo, :tag_Description_Utilisateur,:tag_MotDePasse,:tag_Mail,:tag_AdrrPhotoProfil);";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_Statut" =>"0",
				"tag_Pseudo" =>$P,
				"tag_Description_Utilisateur" =>$DecU,
				"tag_MotDePasse" =>$Mdp,
				"tag_Mail" =>$M,
				"tag_AdrrPhotoProfil" =>$adressPhotoProfil,
			);
			$IdU = Utilisateur::NombreUtilisateur();
			$IdU[0]=$IdU[0]+1;
			$valeurs["tag_IDUtilisateur"] = $IdU[0];
			
			try{
				$req_prep->execute($valeurs);
				return true;
			} catch (PDOException $e){
				echo "erreur : ".$e->getMessage()."<br>";
				return false;
			}
		}
			
		public static function ObtenirIDUtilisateur($P,$Mdp){
			$requetePreparee = "SELECT IDUtilisateur FROM Utilisateur WHERE Pseudo = :tag_Pseudo AND Mot_de_passe = :tag_Mdp;";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array(
				"tag_Pseudo" =>$P,
				"tag_Mdp" =>$Mdp,
			);
			
			try{
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_ASSOC);
				$l = $req_prep->fetch();
				if(!$l) 
					return false;
				return $l;
			}catch (PDOException $e){
				echo "erreur : ".$e->getMessage()."<br>";
				return false;
			} 
		}
		
		public static function InfoUtilisateur($IDUtilisateur){
			$requetePreparee = "SELECT * FROM Utilisateur WHERE IDUtilisateur = :tag_ID";
			$req_prep = Connexion::pdo()->prepare($requetePreparee);
			$valeurs = array("tag_ID" =>$IDUtilisateur);
			
			try{
				$req_prep->execute($valeurs);
				$req_prep->setFetchMode(PDO::FETCH_CLASS,'Utilisateur');
				$l = $req_prep->fetch();
				if(!$l) 
					return false;
				return $l;
			}catch (PDOException $e){
				echo "erreur : ".$e->getMessage()."<br>";
				return false;
			} 
		}
	}

?>