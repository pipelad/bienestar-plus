<?php
	//funcion para crear items en el slider
	function categSlide($categoria) {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		//obtener la sede en la que hizo click, si es home la sede es bogota (1)
		if(array_key_exists("sede", $_GET)) {
			$sede = $_GET['sede'];
		} else {
			$sede = 1;
		}
		$sqlString = 'SELECT *  FROM `items` WHERE `categoria`="'.$categoria.'" AND `sede`="'.$sede.'"';
		$sql = $con->prepare($sqlString);
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				if($row->tipo === 'video') {
					// limpiar el texto de guardado para eliminiar el "../" necesario en el admin pero no en el front
					$cleanLink = substr($row->mini_video, 3);
					echo('
						<a class="video-popup sede_'.$row->sede.'" data-id="'.$row->url_video.'">
							<div class="bp_desplazo">
								<img src="'.$cleanLink.'" >
							</div>
						</a>
					');
				}
				if($row->tipo === 'fotos') {
					// decodificar el item de miniaturas
					$minis = json_decode($row->mini_fotos);
					// extraer la primera miniatura para mostrar y  limpiar el texto de guardado para eliminiar el "../" necesario en el admin pero no en el front
					$firstmini = substr($minis[0], 3);
					echo('
						<a href="?categ='.$row->categoria.'&sede='.$row->sede.'&itemid='.$row->id.'" class="foto-popup sede_'.$row->sede.'">
							<div class="bp_desplazo">
								<img src="'.$firstmini.'" >
							</div>
						</a>
					');
				}
			}
		}
	}
	//funcion para crear el slider por cada categoria
	function categorias() {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `categoria`');
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('
					<div class="slide" >
						<div class="slide--title">
							'.$row->nombre.'
						</div>
						<div class="slide--controls-left slide--controls">
							<img src="images/base/flecha-left.svg" data-id="slide_'.$row->id.'">
						</div>
						<div class="slide--container">
							<div id="slide_'.$row->id.'" class="slide--items-wrap">
								<div class="slide--items">
							
				');
				categSlide($row->id);
				echo('	
								</div>
							</div>
						</div>
						<div class="slide--controls-right slide--controls">
							<img src="images/base/flecha-right.svg" data-id="slide_'.$row->id.'">
						</div>
					</div>
				');
			} 
		}
	}

?>

<div class="categorias">
	<?php categorias() ?>
</div>

<script type="text/javascript" src="js/componentes/slides.js"></script>