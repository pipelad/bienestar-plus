<?php
	define('bienestar_plus', TRUE);
?>
<!DOCTYPE html>
<html translate="no" lang="es">
<head>
	<?php include('vistas/ui//head.php'); ?>
</head>
<body>
	<div id="modal" class="hidden">
		
	</div>
	<?php
		// header global sin importar el GET
		if(!file_exists('admin/config/config.php')) {
			echo '<h1>No existe archivo de configuaración contactese con el administrador</h1>';
			return;
		}

		include('admin/config/config.php');

		include('vistas/ui/header.php');

		if(array_key_exists("categ", $_GET)) {
			include('vistas/pages/categoria.php');
		}
		else if(array_key_exists("concurso", $_GET)) {
			include('vistas/pages/concurso.php');
		}
		else if(array_key_exists("contacto", $_GET)) {
			include('vistas/pages/contacto.php');
		}
		else {
			if(array_key_exists("sede", $_GET)) {
				// Si cambia la sede no cargar el loader
				include('vistas/pages/home.php');
				include('vistas/ui/footer.php');
				return;
			}
			// Solo cargar el loader en home
			echo('<div id="load-phase">');
			include('vistas/pages/load.php');
			echo('</div>');
			// Cargar vista home
			include('vistas/pages/home.php');
		}

		include('vistas/ui/footer.php');
	?>
</body>
</html>