<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Créer recette </title>
	<link rel="icon" type="image/png" href="img/toque.ico" />
	<link rel="stylesheet" href="css/form.css" media="screen" type="text/css" />
</head>

<body>
	<div id="container">
		<form action="routeur.php" method="post" enctype="multipart/form-data" autocomplete="off">
			<input type="hidden" name="action" value="createdRecette">
			
			<?php 
				//print_r($_SESSION);
				$Auteur=$_SESSION["Pseudo"];
				$IDAuteur=$_SESSION["IDUtilisateur"];
				echo "<input type='hidden' name='Auteur' value='".$Auteur."' autocomplete='off' required>";
				echo "<input type='hidden' name='IDAuteur' value='".$IDAuteur."' autocomplete='off' required>";
				//print_r($IDAuteur);
			?>
			<center><label>Créer une recette</label></center>
			<p>
				<label>• Nom de la recette :</label>
				<input type="text" name='nomRecette' autocomplete="off" required>
			</p>
			</br>
			
			<p>
				<label>• Temps de préparation minimal nécessaire en minutes :</label>
				<input type="number" name="tempsMinutes" autocomplete="off" min="0" required>
			</p>
			</br>
			
			<p>
				<label>• Description de la recette :</label>
				<input type="text" name="Description" autocomplete="off" required>
			</p>
			</br>
			
			<p>
				<label>• Type de plat : </label>
				
				<select type="text" name="typePlat" autocomplete="off" required>
					<option>Entrée</option>
					<option>Plat</option>
					<option>Dessert</option>
					<option>Apéritif</option>
					<option>Autre</option>
				</select>
				
				<label>ㅤㅤㅤㅤ• Saveur : </label>
				
				<select type="text" name="saveur" autocomplete="off" required>
					<option>Salé</option>
					<option>Sucré</option>
					<option>Amer</option>
					<option>Acide</option>
					<option>Umami</option>
				</select>
				
				</br></br>
				<label>• Régime : </label>
				
				<select type="text" name="regime" autocomplete="off" required>
					<option>Omnivore</option>
					<option>Végétarien</option>
					<option>Végan</option>
					<option>Sans Gluten</option>
					<option>Autre</option>
				</select>
			</p>
			</br>
			
			<p>
				<label>• Niveau de difficulté (de 0 à 9) :</label>
				<input type="number" name="Difficulte" autocomplete="off" min="0" required>
			</p>
			</br>		
			
			<!-- Ingrédients -->
			<label>• Ingrédients :</label>
			<?php
				echo '<p id="partie_ingredients">';
				echo	'<label>Ingrédient n°1 : </label>';
				echo 	'<input onclick="createInputIngredient(2); this.onclick=null" type="$current_text" name="ingredient[]" autocomplete="off" required>';
				echo	'ㅤ<label>Qte : </label>';
				echo 	'<input type="$current_text" name="ingredientQte[]" autocomplete="off" size ="1" required>';
				echo '</p>';
				
				$j=2;
			?>
			</br>
			
			<!-- Ustensile -->
			<label>• Ustensiles :</label>
			<?php
				echo '<p id="partie_ustensiles">';
				echo	'<label>Ustensile n°1 : </label>';
				echo 	'<input onclick="createInputUstensiles(2); this.onclick=null" type="$current_text" name="ustensiles[]" autocomplete="off" required>';
				echo	'ㅤ<label>Qte : </label>';
				echo 	'<input type="$current_text" name="ustensilesQte[]" autocomplete="off" size ="1" required>';
				echo '</p>';
				
				$k=2;
			?>
			</br>
			
			<label>• Etapes :</label>
			<?php
				echo '<p id="partie_etapes">';
				echo	'<label>Etape n°1 : </label>';
				echo 	'<input onclick="createInput(2); this.onclick=null" type="$current_text" name="etape[]" autocomplete="off" required>';
				echo '</p>';
				
				$i=2;
			?>
			</br>
			
			<p>
				<label>• Photo de la recette :</label>
				<input type="file" name="NomPhoto" id="NomPhoto" autocomplete="off" required>
			</p>
			</br>
	
		<script>
			function createInput(i) {
				var p = document.createElement('p');
				var l = document.createElement('Label');
				l.innerHTML="Etape n°" + i + " : ";

				//Création d'un nouvel input
				var x = document.createElement("INPUT");
				x.setAttribute("type", "input");
				i++;
				x.setAttribute("onclick", "createInput("+i+"); this.onclick=null");
				x.setAttribute("value", ""); 
				i--;
				x.setAttribute("name","etape[]");

				document.getElementById('partie_etapes').appendChild(p);
				document.getElementById("partie_etapes").appendChild(l);
				document.getElementById("partie_etapes").appendChild(x); 
				
				i=i+2;
			}
			
			function createInputIngredient(j) {
				var p = document.createElement('p');
				var l = document.createElement('Label');
				l.innerHTML="Ingrédient n°" + j + " : ";
				var l2 = document.createElement('Label');
				l2.innerHTML="ㅤQte : ";
				
				//Création d'un nouvel input
				var x = document.createElement("INPUT");
				var y = document.createElement("INPUT");
				x.setAttribute("type", "input");
				y.setAttribute("type", "input");
				j++;
				x.setAttribute("onclick", "createInputIngredient("+j+"); this.onclick=null");
				x.setAttribute("value", ""); 
				
				y.setAttribute("value", ""); 
				y.setAttribute("size","1");
				j--;
				x.setAttribute("name","ingredient[]");
				y.setAttribute("name","ingredientQte[]");

				document.getElementById('partie_ingredients').appendChild(p);
				document.getElementById("partie_ingredients").appendChild(l);
				document.getElementById("partie_ingredients").appendChild(x); 
				document.getElementById("partie_ingredients").appendChild(l2);
				document.getElementById("partie_ingredients").appendChild(y);
				
				j=j+2;
			}
			
			function createInputUstensiles(k) {
				var p = document.createElement('p');
				var l = document.createElement('Label');
				l.innerHTML="Ustensile n°" + k + " : ";
				var l2 = document.createElement('Label');
				l2.innerHTML="ㅤQte : ";
				
				//Création d'un nouvel input
				var x = document.createElement("INPUT");
				var y = document.createElement("INPUT");
				x.setAttribute("type", "input");
				y.setAttribute("type", "input");
				k++;
				x.setAttribute("onclick", "createInputUstensiles("+k+"); this.onclick=null");
				x.setAttribute("value", ""); 
				
				y.setAttribute("value", ""); 
				y.setAttribute("size","1");
				k--;
				x.setAttribute("name","ustensiles[]");
				y.setAttribute("name","ustensilesQte[]");

				document.getElementById('partie_ustensiles').appendChild(p);
				document.getElementById('partie_ustensiles').appendChild(l);
				document.getElementById('partie_ustensiles').appendChild(x); 
				document.getElementById('partie_ustensiles').appendChild(l2);
				document.getElementById("partie_ustensiles").appendChild(y);
				
				k=k+2;
			}
		</script>
		<input type="submit" value="Envoyer" name="Envoyer"/>
	</form>	
	</div>
</body>
</html>