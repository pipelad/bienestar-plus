<?php
	function bannerItems() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `banner` ORDER BY `id` DESC');
		if($sql->execute()) {
			// Determina si hay items existentes y los muestra
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('
					<div class="item">
						<div class="item--imagen">
							<img src="'.$row->url.'">
						</div>
						<div class="item--controls">
							<a href="?editbanner='.$row->id.'" class="edit">
								Editar
							</a>
							<form method="POST">
								<input hidden value="'.$row->url.'" name="url">
								<input hidden value="'.$row->id.'" name="delete">
								<button>Borrar</button>
							</form>
						</div>
					</div>
				');
			}
		}
	}

	function editItem() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `banner` WHERE `id`=:id LIMIT 1');
		$id = $_GET['editbanner'];
		$sql->bindValue(':id', $id);
		$sql->execute();
		if($row = $sql->fetch(PDO::FETCH_OBJ)) {
			echo('
				<form method="POST" class="banner--edit" id="bannerform" action="?banner" enctype="multipart/form-data">
					<div class="current">
						<img src="'.$row->url.'">
					</div>
					<div id="banner--data">
						<div class="row">
							<label for="fotoUp">Foto</label>
							<input type="file" class="bannerfoto" name="fotoUp" accept="image/*" required>
						</div>
						<input hidden value="'.$row->url.'" name="current">
						<input hidden value="'.$row->id.'" name="id">
						<input hidden value="edit" name="tipo">
					</div>
					<div class="submit">
						<button id="submit">Guardar</button>
						<a class="cancelar" href="?banner">Cancelar</a>
					</div>
				</form>
			');
		}
	}

	if(isset($_POST['tipo']) && $_POST['tipo'] === 'new') {
		$imagesNames = $_FILES['foto']['name'];
		$imageCount = count($imagesNames);
		$foto = '../images/banner/';
		for($i = 0; $i < $imageCount; $i++) {
			$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$url = $imagesNames[$i];
			if ($url != "") {
				if ($_FILES['foto']['error'][$i] > 0) {
					// si hay un error subiendo el archivo
					$error .= "Se presentó un error con la foto ".$_FILES['foto']['error'][$i].".";
					return;
				} 
				if($_FILES['foto']['size'][$i] > 1300000) {
					// si supera el tamaño máximo
					$error .= "La foto ".$url." supera el límite máximo de 1.3MB, corrija esto antes de continuar.";
					return;
				}
				$foto .= $url;
				if(file_exists($foto)) {
					$error .= "Este archivo ya existe, confirme el nombre";
					return;
				}
				//////// move_uploaded_file guarda la imagen permanentemente en el lugar adecuado.
				if(move_uploaded_file($_FILES['foto']['tmp_name'][$i], $foto)) {
					$sql = $con->prepare('INSERT INTO `banner` (`url`) VALUES (:url)');
					$sql->bindValue(':url', $foto);
					if($sql->execute()) {
						$advertencia .= 'Item:'.$url.' agregado al banner correctamente';
						$foto = '../images/banner/';
					}
				} else {
					$error .= "Se presentó un error al subir el archivo, contáctese con el administrador.";
					return;
				}

			}
		}
	}

	if(isset($_POST['tipo']) && $_POST['tipo'] === 'edit') {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$id = $_POST['id'];
		$current = $_POST['current'];
		if(file_exists($current)) {
			unlink($current);
		}
		$imageName = $_FILES['fotoUp']['name'];
		$foto = '../images/banner/';
		if ($imageName != "") {
			if ($_FILES['fotoUp']['error'] > 0) {
				// si hay un error subiendo el archivo
				$error = "Se presentó un error con la foto ".$_FILES['fotoUp']['error'].".";
				return;
			} 
			if($_FILES['fotoUp']['size'] > 1300000) {
				// si supera el tamaño máximo
				$error = "La foto supera el límite máximo de 1.3MB, corrija esto antes de continuar.";
				return;
			}
			$foto .= $imageName;
			//////// move_uploaded_file guarda la imagen permanentemente en el lugar adecuado.
			if(move_uploaded_file($_FILES['fotoUp']['tmp_name'], $foto)) {
				$sql = $con->prepare('UPDATE `banner` SET `url` = :url WHERE `id`=:id LIMIT 1');
				if($sql->execute(array(':url' => $foto, ':id' => $id))) {
					$advertencia = 'Item:'.$imageName.' agregado al banner correctamente';
				}
			} else {
				$error = "Se presentó un error al subir el archivo, contáctese con el administrador.";
				return;
			}
		}
	}

	if(isset($_POST['delete']) && $_POST['delete'] !== '') {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('DELETE FROM `banner` WHERE `id` = :id');
		$id = $_POST['delete'];
		$current = $_POST['url'];
		$sql->bindValue(':id', $id);
		if($sql->execute()) {
			$advertencia = 'Item:'.$current.' borrado exitosamente';
			if(file_exists($current)) {
				unlink($current);
			}
		} else {
			$error = 'Se presentó un error al borrar el item, intente más tarde';
		}
	}

?>