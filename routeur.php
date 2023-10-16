<?php 
	require_once("controller/controllerGeneral.php");
	
	ini_set('session.cache_limiter','public');
	session_cache_limiter(false);
	
	if(session_status() === PHP_SESSION_ACTIVE) {
		session_start();
	}
	
	
	
	$action = "readAll"; //Il faut changer et mettre un truc pour l'acceuil pour l'instant l'acceuil affiche toutes les recettes
	if (isset($_GET["action"]) && in_array($_GET["action"],get_class_methods("controllerGeneral"))) 
		$action = $_GET["action"];
	elseif (isset($_POST["action"]) && in_array($_POST["action"],get_class_methods("controllerGeneral"))) 
		$action = $_POST["action"];

	controllerGeneral::$action();
?>
