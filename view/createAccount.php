<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Creation d'un compte</title>
		<link rel="icon" type="image/png" href="img/toque.ico" />
		<link rel="stylesheet" href="css/form.css" media="screen" type="text/css" />
	</head>
	<body>
		<div id="container">
			<form action="routeur.php" method="post" enctype="multipart/form-data">
				<h1>Création de compte</h1>
				<input type="hidden" name="action" value="createdAccount">
				<p>
					<label>Mail :</label>
					<input type="text" placeholder="Entrer votre mail" name="Mail" autocomplete="off" required>
				</p>
				<p>
					<label>Pseudo :</label>
					<input type="text" placeholder="Entrer votre pseudo" name="Pseudo" autocomplete="off" required>
				</p>
				<p>
					<label>Mot de passe :</label>
					<input type="password" placeholder="Entrer votre mot de passe" name="Mdp" autocomplete="off" required>
				</p>
				<p>
					<label>Description : (Pas obligatoire) </label>
					<input type="text" name="DecU">
				</p>
				<p>
					<label>Photo de profil :</label>
					<input type="file" name="NomPhoto" id="NomPhoto" autocomplete="off" required>
				</p>
				
				<p>
					<input type="submit" name="envoyer" value="créer le compte">
				</p>
			</form>
		</div>
	</body>
</html>
