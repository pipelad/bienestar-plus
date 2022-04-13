<?php
	function deleteFolder($dirname) {
		array_map("unlink", glob($dirname."/*"));
		array_map("rmdir", glob($dirname."/*")); 
		rmdir($dirname);
	}

	function borrarBnt($id) {
		echo('
			<form id="delete-item-form" method="POST" action="./">
				<input hidden value="'.$id.'" name="delete-item">
				<button>Borrar este item</button>
			</form>
		');
	}

	function editCateg($id) {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `categoria`');
		$current = '';
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				if($row->id === $id) {
					$current = 'selected';
				}
				echo('<option '.$current.' value="'.$row->id.'">'.$row->nombre.'</option>');
				$current = '';
			}
		}
	}

	function editSede($id) {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `sedes`');
		$current = '';
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				if($row->id === $id) {
					$current = 'selected';
				}
				echo('<option '.$current.' value="'.$row->id.'">'.$row->nombre.'</option>');
				$current = '';
			}
		}
	}

	if(isset($_POST['delete-foto']) && $_POST['delete-foto'] === 'true') {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		// crear array de fotografias para la tabla
		$fotos = explode(";", $_POST['fotos']);
		$minis = explode(";", $_POST['minis']);
		// extraer el key de la foto a borrar
		$key = $_POST['arraykey'];
		$id = $_POST['id'];
		// url de la foto y la miniatura para borrar archivos
		$minusFoto = $fotos[$key];
		$minusMini = $minis[$key];
		// extraer url de los array para hacer update en la base de datos
		unset($fotos[$key]);
		unset($minis[$key]);
		// codificar los array preparados para insertar
		$fotos = array_values($fotos);
		$minis = array_values($minis);
		$fotos = json_encode($fotos);
		$minis = json_encode($minis);
		$sql = $con->prepare('UPDATE `items` SET `url_fotos`=:fotos, `mini_fotos`=:minis WHERE `id`=:id');
		if($sql->execute(array(':fotos' => $fotos, ':minis' => $minis, ':id' => $id))) {
			if(file_exists($minusFoto)) {
				unlink($minusFoto);
			}
			if(file_exists($minusMini)) {
				unlink($minusMini);
			}
			$advertencia = 'Foto '.$minusFoto.' borrada exitosamente';
			if($error !== '') {
				$error = '';
			}
		} else {
			$error = 'No fue posible borrar esta foto';
		}
		$_POST = array();
		$_FILES = array();
	}

	if(isset($_POST['delete-item']) && $_POST['delete-item'] !== '') {
		//primero conectarse a la base de datos para obtener los archivos que se deben borrar del servidor
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `items` WHERE `id` = :id');
		$id = $_POST['delete-item'];
		$tipoItem = '';
		$sql->bindValue(':id', $id);
		$sql->execute();
		if($row = $sql->fetch(PDO::FETCH_OBJ)) {
			if($row->tipo === 'fotos') {
				// si es fotos decodificar las fotos y las miniaturas, para obtener el folder a borrar
				$fotos = json_decode($row->url_fotos);
				$strArray = explode("/",$fotos[0]);
				$strImg = array_pop($strArray);
				$folder = implode("/", $strArray);
				// borrar el folder y cualquier otro archivo que no sea foto
				deleteFolder($folder);
				$tipoItem = 'Evento';
			}
			if($row->tipo === 'video') {
				// extraer el folder del item para luego borrar.
				$strArray = explode("/",$row->mini_video);
				$strImg = array_pop($strArray);
				$folder = implode("/", $strArray);
				// borrar el folder y cualquier otro archivo que no sea foto
				deleteFolder($folder);
				$tipoItem = 'Video';
			}
		}
		// borrar la entrada en la base de datos
		$sqlDelete = $con->prepare('DELETE FROM `items` WHERE `id` = :id');
		$sqlDelete->bindValue(':id', $id);
		if($sqlDelete->execute()) {
			$advertencia = $tipoItem.' eliminado exitosamente';
		}
	}

	if(isset($_POST['edit-item']) && $_POST['edit-item'] !== "") {
		$id = $_POST['edit-item'];
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		// preparar la conexión a la base de datos, el Inner join compara los id de categoria y sede del item con las tablas de sedes y categorias para traer el nombre dinámicamente.

		if($_POST['tipo'] === 'video') {
			// primero cambiar la miniatura, ya que este requiere cambiar archivos tambien.
			if($_FILES['miniatura']['name']) {
				// si hay miniatura, subir la nueva y borrar la antigua:
				$fotoName = $_FILES['miniatura']['name'];
				if ($fotoName != "") {
					if ($_FILES['miniatura']['error'] > 0) {
						// si hay un error subiendo el archivo
						$error = "Se presentó un error con la foto ".$_FILES['miniatura']['error'].".";
						return;
					}
					if($_FILES['miniatura']['size'] > 300000) {
						// si supera el tamaño máximo
						$error = "La foto supera el límite máximo de 300kB, corrija esto antes de continuar.";
						return;
					}
					//////// el folder de la miniatura:
					$folderName = $_POST['folder'];
					//////// move_uploaded_file guarda la imagen permanentemente en el lugar adecuado.
					if(move_uploaded_file($_FILES['miniatura']['tmp_name'], $folderName.'/'.$fotoName)) {
						$foto = $folderName.'/';
						$foto .= $fotoName;
						$sql = $con->prepare('UPDATE `items` SET `mini_video`= :foto WHERE `id`= :id');
						if($sql->execute(array(':foto'=>$foto, ':id'=>$id))) {
							$advertencia .= 'Miniatura actualizada.';
							$current = $_POST['actual'];
							if(file_exists($current)) {
								unlink($current);
							}
						}
					} else {
						$error = "Se presentó un error al subir el archivo, contáctese con el administrador.";
						return;
					}
				}
			}
			$sql = $con->prepare('UPDATE `items` SET `nombre` = :nombre, `categoria` = :categoria, `sede` = :sede, `url_video` = :video, `fecha` = :fecha WHERE  `id`= :id');
			$fecha = date('Y-m-d', strtotime($_POST['fecha']));
			if ($sql->execute(array(
				':nombre' => $_POST['nombre'],
				':categoria' => $_POST['categoria'],
				':sede' => $_POST['sede'],
				':video' => $_POST['videourl'],
				':fecha' => $fecha,
				':id' => $id
			))) {
				$advertencia .= 'Video actualizado exitosamente';
			} else {
				//$sql->debugDumpParams();
				$error = 'El Video no pudo ser actualizado a la base de datos intente nuevamente';
			}
		}
		if($_POST['tipo'] === 'fotos') {
			if($_FILES['img_gal']['name'] && $_FILES['img_gal']['name'][1] !== '') {
				$fotos = explode(";", $_POST['fotos-actuales']);
				$minis = explode(";", $_POST['minis-actuales']);
				$fotosArr = $_FILES['img_gal']['name']; // array de todas las fotos
				$errores = $_FILES['img_gal']['error']; // array de todos los errores
				$size = $_FILES['img_gal']['size']; // array de todos los tamaños
				$countFoto = count($fotosArr);
				//////// Construir folder de miniaturas en el video
				$folderName = $_POST['folder'];
				for($i=1; $i<=$countFoto; $i++) {
					if($errores[$i] > 0) {
						$error .= "Se presentó un error con la foto ".$errores[$i].".";
						continue;
					}
					if($size[$i] > 1000000) {
						$error .= 'La foto '.$fileName.' supera el límite máximo de 1MB, corrija esto antes de continuar.';
						continue;
					}
					$fileName = $fotosArr[$i];
					if (file_exists($folderName.'/'.$fileName)) {
						$error .= 'El archivo '.$fileName.' ya existe. ';
						continue;
					}
					if(move_uploaded_file($_FILES['img_gal']['tmp_name'][$i], $folderName.'/'.$fileName)) {
						//una vez subida la foto genterar la info para la columna de fotografias y miniautras
						//item para fotos:
						$foto = $folderName.'/';
						$foto .= $fileName;
						array_push($fotos, $foto);
						// item para miniaturas
						// opciones de miniatura:
						$src= $foto;
						$dest = $folderName.'/_miniatura_'.$fileName;
						$desired_width="400";
						// usar la función para crear la miniatura
						crear__mini($src, $dest, $desired_width);
						array_push($minis, $dest);
						$advertencia .= 'foto:'.$fileName.', ';
					} else {
						$error .= 'Se presentó un error al subir el archivo '.$fileName.', contáctese con el administrador.';
					}
				}
				// codificar las fotos para guardar en una sola tabla.
				$fotos = json_encode($fotos);
				// codificar las miniaturas para guardar en una sola tabla.
				$minis = json_encode($minis);
				$sql = $con->prepare('UPDATE `items` SET `url_fotos`=:fotos, `mini_fotos`=:minis WHERE `id`=:id');
				if($sql->execute(array(':fotos' => $fotos, ':minis' => $minis, ':id' => $id))) {
					$advertencia .= ' agregadas exitosamente';
				}
			}
			$sql = $con->prepare('UPDATE `items` SET `nombre` = :nombre, `categoria` = :categoria, `sede` = :sede, `fecha` = :fecha WHERE  `id`= :id');
			$fecha = date('Y-m-d', strtotime($_POST['fecha']));
			if ($sql->execute(array(
				':nombre' => $_POST['nombre'],
				':categoria' => $_POST['categoria'],
				':sede' => $_POST['sede'],
				':fecha' => $fecha,
				':id' => $id
			))) {
				$advertencia .= 'Evento actualizado exitosamente';
				if($error !== '') {
					$error = '';
				}
			} else {
				//$sql->debugDumpParams();
				$error = 'El Evento no pudo ser actualizado a la base de datos intente nuevamente';
			}
		}
	}
?>