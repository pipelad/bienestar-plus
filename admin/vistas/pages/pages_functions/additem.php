<?php

	if(!defined('bienestar_plus')) {
	   header('Location: index.php');
	}

	function sedes() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `sedes`');
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('<option value="'.$row->id.'">'.$row->nombre.'</option>');
			}
		}
	}

	function categorias() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `categoria`');
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('<option value="'.$row->id.'">'.$row->nombre.'</option>');
			}
		}
	}

	function crear__mini($src, $dest, $desired_width) {
	    // Leer la fuente de la miniatura , alto y ancho
	    $source_image = imagecreatefromjpeg($src);
	    $width = imagesx($source_image);
	    $height = imagesy($source_image);
	    // encontrar el alto de la miniatura
	    $desired_height = floor($height * ($desired_width / $width));
	    // crear imagen virtual temporal
	    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	    // copiar fuente a tamaño redimencionado
	    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	    // crear imagen miniatura
	    imagejpeg($virtual_image, $dest);
	}

	if(isset($_POST['submit']) && $_POST['submit'] === '1') {
		// Preparar conexión a la base de daots
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('
			INSERT INTO `items` (`nombre`, `categoria`, `sede`, `tipo`, `url_video`, `mini_video`, `url_fotos`, `mini_fotos`, `fecha`) 
			VALUES (:nombre, :categoria, :sede, :tipo, :video, :minivideo, :fotos, :minifotos, :fecha)'
		);
		// Variables comunes
		$nombre = $_POST['nombre'];
		$categoria = $_POST['categoria'];
		$sede = $_POST['sede'];
		$tipo = $_POST['tipo'];
		$fecha = date('Y-m-d', strtotime($_POST['fecha']));
		// Si el tipo es un video
		if($tipo === 'video') {
			$foto = '';
			if (array_key_exists('miniatura', $_FILES) && $_FILES['miniatura']['name'] === '')  {
				$error = 'Es necesario tener una miniatura';
				return;
			} 
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
				//////// Construir folder de miniaturas en el video
				$folderName = '../images/videos/video_';
				$folderName .= date('y-m-d-H-i');
				if (!file_exists($folderName)) {
				    mkdir($folderName, 0777, true);
				}
				//////// Construir index.html para evitar accesos extraños al folder
				$indexHtml = $folderName.'/index.html';
				if(!file_exists($indexHtml)) {
					$file = fopen($indexHtml, 'w') or die("can't open file");
					fclose($file);
				}
				//////// move_uploaded_file guarda la imagen permanentemente en el lugar adecuado.
				if(move_uploaded_file($_FILES['miniatura']['tmp_name'], $folderName.'/'.$fotoName)) {
					$foto = $folderName.'/';
					$foto .= $fotoName;
				} else {
					$error = "Se presentó un error al subir el archivo, contáctese con el administrador.";
					return;
				}
			}

			// ejecutar guardado a base de datos
			if($foto !== '') {
				if ($sql->execute(array(
					':nombre' => $nombre, 
					':categoria' => $categoria, 
					':sede' => $sede,
					':tipo' => $tipo,
					':video' => $_POST['videourl'],
					':minivideo' => $foto,
					':fotos' => '',
					':minifotos' => '',
					':fecha' => $fecha
				))) {
					$advertencia = 'Video agregado exitosamente';
				} else {
					//$sql->debugDumpParams();
					$error = 'El Video no pudo ser agregado a la base de datos intente nuevamente';
				}
			}
		}
		// Si el tipo es una foto (galeria de fotos)
		if($tipo === 'fotos') {
			//print_r($_FILES);
			$fotos = [];
			$minis = [];
			$fotosArr = $_FILES['img_gal']['name']; // array de todas las fotos
			$errores = $_FILES['img_gal']['error']; // array de todos los errores
			$size = $_FILES['img_gal']['size']; // array de todos los tamaños
			$countFoto = count($fotosArr);

			if ($fotosArr[1] === '')  {
				$error = 'Es necesario tener al menos una foto';
				return;
			}

			//////// Construir folder de miniaturas en el video
			$folderName = '../images/fotos/actividad_';
			$folderName .= date('y-m-d-H-i');
			if (!file_exists($folderName)) {
			    mkdir($folderName, 0777, true);
			}
			//////// Construir index.html para evitar accesos extraños al folder
			$indexHtml = $folderName.'/index.html';
			if(!file_exists($indexHtml)) {
				$file = fopen($indexHtml, 'w') or die("can't open file");
				fclose($file);
			}

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
					$foto = $folderName.'/';
					$foto .= $fileName;
					array_push($fotos, $foto);
					$src= $foto;
					$dest = $folderName.'/_miniatura_'.$fileName;
					$desired_width="400";
					crear__mini($src, $dest, $desired_width);
					array_push($minis, $dest);
				} else {
					$error .= 'Se presentó un error al subir el archivo '.$fileName.', contáctese con el administrador.';
				}
			}

			if($error !== '') {
				$error .= ' Edite el evento creado para correjir estos errores';
			}
			// codificar las fotos para guardar en una sola tabla.
			$fotos = json_encode($fotos);

			// codificar las miniaturas para guardar en una sola tabla.
			$minis = json_encode($minis);

			// ejecutar guardado a base de datos
			if($fotos !== '') {
				if ($sql->execute(array(
					':nombre' => $nombre, 
					':categoria' => $categoria, 
					':sede' => $sede,
					':tipo' => $tipo,
					':video' => '',
					':minivideo' => '',
					':fotos' => $fotos,
					':minifotos' => $minis,
					':fecha' => $fecha
				))) {
					$advertencia = 'Evento agregado exitosamente';
				} else {
					//$sql->debugDumpParams();
					$error = 'El Evento no pudo ser agregado a la base de datos intente nuevamente';
				}
			}
		}
	}



?>