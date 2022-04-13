<div class="edititem">
<?php
	$id = $_GET['edit'];
	$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	// preparar la conexión a la base de datos, el Inner join compara los id de categoria y sede del item con las tablas de sedes y categorias para traer el nombre dinámicamente.
	$sql = $con->prepare('
		SELECT item.id AS id, item.nombre AS nombre, categoria.nombre AS categoria, sede.nombre AS sede, item.tipo AS tipo, item.fecha AS fecha, item.url_video AS video, item.mini_video AS miniatura, item.url_fotos AS fotos, item.mini_fotos AS miniaturas, item.categoria AS categid, item.sede AS sedeid
		FROM `items` item
		INNER JOIN `categoria`categoria ON item.categoria = categoria.id
		INNER JOIN `sedes` sede ON item.sede = sede.id
		WHERE  item.id= :id
	');
	$sql->bindValue(':id', $id);
	$sql->execute();
	if($row = $sql->fetch(PDO::FETCH_OBJ)) {
		//Generar un formulario separadao para tipo foto y tipo video, con esto evitar errores por parte del usuario limitando su interaccion
		echo('
			<div class="title">
				<h2>Editar '.$row->tipo.' '.$row->nombre.', de la sede: '.$row->sede.'</h2>
		');
		//boton borrar item.
		borrarBnt($id);
		if(array_key_exists("tipo", $_GET) && $_GET['tipo'] === 'fotos') {
			$fotosArray = json_decode($row->fotos);
			$minisArray = json_decode($row->miniaturas);
			$countFotos = count($fotosArray);
			echo('</div><div class="content-data fotos-edit">');
			for($i=0;$i < $countFotos; $i++) {
				echo('
					<div class="row">
						<div class="row--image">
							<img src="'.$minisArray[$i].'">
						</div>
						<div class="row--control">
							<form class="foto-delete" method="POST" enctype="multipart/form-data">
								<input hidden name="delete-foto" value="true">
								<input hidden name="id" value="'.$id.'">
								<input hidden name="fotos" value="'.implode(";", $fotosArray).'">
								<input hidden name="minis" value="'.implode(";", $minisArray).'">
								<input hidden name="arraykey" value="'.$i.'">
								<button>Borrar</button>
							</form>
						</div>
					</div>
				');
			}
		}
		echo('
			</div>
			<form method="POST" class="addform" id="editform" action="./" enctype="multipart/form-data">
				<div class="addform--info">
					<div class="basic-data">
						<div class="row">
							<label for="nombre">Nombre</label>
							<input type="text" name="nombre" value="'.$row->nombre.'" required>
						</div>
						<div class="row">
							<label for="categoria">Categoria</label>
							<select name="categoria" id="cate" required>
		');
		editCateg($row->categid);
		echo('		
							</select>
						</div>
						<div class="row">
							<label for="sede">Sede</label>
							<select name="sede" id="cate" required>
		');
		editSede($row->sedeid);
		echo('			
							</select>
						</div>
						<div class="row">
							<label for="fecha">Fecha</label>
							<input type="date" id="fecha" name="fecha" value="'.$row->fecha.'" required>
						</div>
					</div>
		');
		// en caso de ser video
		if(array_key_exists("tipo", $_GET) && $_GET['tipo'] === 'video') {
			$strArray = explode("/",$row->miniatura);
			$strImg = array_pop($strArray);
			$folder = implode("/", $strArray);
			echo('
				<div class="content-data video-edit">
					<div class="miniatura">
						<img src="'.$row->miniatura.'">
					</div>
					<div id="video">
						<h3>Video</h3>
						<div class="row">
							<label for="url-video">url</label>
							<input hidden name="tipo" value="video">
							<input type="text" id="video-url" name="videourl" value="'.$row->video.'">
						</div>
						<div class="row">
							<label for="miniatura">Miniatura</label>
							<input hidden name="actual" value="'.$row->miniatura.'">
							<input hidden name="folder" value="'.$folder.'">
							<input type="file" id="video-min" name="miniatura" accept="image/*">
						</div>	
					</div>
				</div>
			');
		}
		// en caso de ser galeria
		if(array_key_exists("tipo", $_GET) && $_GET['tipo'] === 'fotos') {
			$strArray = explode("/",$fotosArray[0]);
			$strImg = array_pop($strArray);
			$folder = implode("/", $strArray);
			$disabled = '';
			if($countFotos === 12) {
				$disabled = 'disabled'; 
			}
			echo('
				<div class="fotos-new">
					<input hidden name="folder" value="'.$folder.'">
					<input hidden name="fotos-actuales" value="'.implode(";", $fotosArray).'">
					<input hidden name="minis-actuales" value="'.implode(";", $minisArray).'">
					<input hidden name="tipo" value="fotos">
					<div id="galeria--items">
						<div class="row">
							<div class="galeria--items-item"><label for="img_gal_1">Foto:</label><input name="img_gal[1]" '.$disabled.' type="file" accept="image/*"></div>
						</div>
					</div>
					<div id="addgaleryitem">+</div>
				</div>
			');
		}
		echo('
			</div>
				<div class="addform--submit">
					<input hidden name="edit-item" value="'.$row->id.'">
					<button>Actualizar</button>
					<a href="./" id="cancelform">cancelar</a>
				</div>
			</form>
		');
	}
?>

<script type="text/javascript" src="js/edititem.js"></script>
	
</div>
