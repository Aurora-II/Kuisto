<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Connexion</title>
		<link rel="icon" type="image/png" href="img/toque.ico" />
		<link rel="stylesheet" href="css/form.css" media="screen" type="text/css" />
	</head>
	<body>
		<div id="container">
			<form action="routeur.php" method="post">
			<input type="hidden" name="action" value="connected">
				<h1>Connexion</h1>
				<?php
					if(isset($_GET['erreur'])){
						$err = $_GET['erreur'];
						if($err==1)
							echo "<p style='color:red'>Utilisateur ou mot de passe incorrect</p>";
					}
					
					if(isset($_GET['msg'])){
						echo "<center><p>Votre compte à bien été crée</p></center>";
					}
				?>
				
				<p>
					<label>Pseudo :</label>
					<input type="text" placeholder="Entrer votre pseudo" name="Pseudo" required>
				</p>
				<p>
					<label>Mot de passe :</label>
					<input type="password" placeholder="Entrer votre mot de passe" name="Mdp" required>
				</p>
				<p>
					Si vous n'avez pas de compte, <a href='routeur.php?action=createAccount'>créer un compte</a>
					<input type="submit" name="envoyer" value="Se connecter">
				</p>
			</form>
		</div>
	</body>
</html>