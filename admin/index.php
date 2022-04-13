<?php
	define('bienestar_plus', TRUE);

	session_start();
	
	$logedin = false;
?>
<html lang="en">
<head>
	<?php include('vistas/ui/head.php') ?>
</head>
<body>
	<?php 


	if(file_exists("config/config.php")) {


		// incluir config file
		include('config/config.php');

		// incluir funciones
		include('functions/global_functions.php');

		// verificación base de ingreso
		// $logedin = false;

		if (isset($_SESSION) AND array_key_exists("bp_login", $_SESSION)) {
			$logedin = true;
		}

		if ($logedin === false) {

			include('vistas/login.php');

		}  else if ($logedin === true) {

			include('vistas/admin.php');

		}

		/*echo '<div id="debug">Sesion: ';
		print_r($_SESSION);
		echo ' Post: ';
		print_r($_POST);
		echo 'vamos en: '.$_SESSION['paso'];
		echo '</div>';*/

	} else {

		// Imprimir error base de configuración

		echo "Hace falta la configuración";
	}



?>
	
</body>
</html>