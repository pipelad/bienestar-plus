<?php

	function itemTitulo() {
		if(array_key_exists("itemid", $_GET)) {
			$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$sql = $con->prepare('SELECT `nombre` FROM `items` WHERE `id`=:itemid');
			$itemid = $_GET['itemid'];
			$sql->bindValue(':itemid', $itemid);
			if($sql->execute()) {
				if ($row = $sql->fetch(PDO::FETCH_OBJ))  {
					echo ': '.$row->nombre;
				}
			}
		} else {
			echo '';
		}
	}
	
	function categTitulo() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `categoria` WHERE `id`=:categoria');
		$categ = $_GET['categ'];
		$sql->bindValue(':categoria', $categ);
		if($sql->execute()) {
			if ($row = $sql->fetch(PDO::FETCH_OBJ))  {
				echo '<h2>'.$row->nombre;
				itemTitulo();
				echo '</h2>';
			}
		}
	}
	

	function allCategItems() {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `items` WHERE `categoria`=:categoria');
		$categ = $_GET['categ'];
		$sql->bindValue(':categoria', $categ);
		if($sql->execute())  {
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

	function galleryItem() {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `items` WHERE `categoria`=:categoria AND `sede`=:sede AND `id`=:id');
		$categ = $_GET['categ'];
		$sede = $_GET['sede'];
		$id = $_GET['itemid'];
		$fotos = [];
		$minis = [];
		if($sql->execute(array(':categoria' => $categ, ':sede' => $sede, ':id' => $id))) {
			while($row = $sql->fetch(PDO::FETCH_OBJ))  {
				//decodificar fotos y miniaturaz
				$fotos = json_decode($row->url_fotos);
				$minis = json_decode($row->mini_fotos);
				//contar fotos:
				
			}
		}
		$len = count($fotos);
		for($i=0; $i < $len; $i++) {
			echo('
				<a class="foto-popup sede_'.$sede.'" data-id="'.substr($fotos[$i], 3).'" data-index="'.$i.'">
					<div class="bp_desplazo">
						<img src="'.substr($minis[$i], 3).'" >
					</div>
				</a>
			');
		}
	}
?>
<main class="categorias">
	<div class="categorias--title">
		<?php categTitulo() ?>
		<?php 
			if(array_key_exists("itemid", $_GET)) {
				echo('
					<div id="regresar">
						<a onclick="history.back()" class="back-btn"><img src="images/base/flecha-left.svg"> Regresar</a>
					</div>
				');
			}
		?>
	</div>
	<div class="items">
		<?php 
			if(array_key_exists("itemid", $_GET)) {
				galleryItem();
			} else {
				allCategItems();
			}
		?>
	</div>
</main>
<script type="text/javascript" src="js/main.js"></script>