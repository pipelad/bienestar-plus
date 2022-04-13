<?php
	function allItems() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		// preparar la conexión a la base de datos, el Inner join compara los id de categoria y sede del item con las tablas de sedes y categorias para traer el nombre dinámicamente.
		$sql = $con->prepare('
			SELECT item.id AS id, item.nombre AS nombre, categoria.nombre AS categoria, sede.nombre AS sede, item.tipo AS tipo, item.fecha AS fecha 
			FROM `items` item
			INNER JOIN `categoria`categoria ON item.categoria = categoria.id
			INNER JOIN `sedes` sede ON item.sede = sede.id
			ORDER BY `id` DESC
		');
		//ejecutar y contruir el html de cada item, separado de esta forma para legibilidad mantener mismas columnas acá y en itemlist.php para continuidad
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('
					<div class="itemlist--item">
						<div class="itemlist--item-nombre">
							'.$row->nombre.'
						</div>
						<div class="itemlist--item-tipo">
							'.$row->tipo.'
						</div>
						<div class="itemlist--item-cate">
							'.$row->categoria.'
						</div>
						<div class="itemlist--item-sede">
							'.$row->sede.'
						</div>
						<div class="itemlist--item-fecha">
							'.$row->fecha.'
						</div>
						<div class="itemlist--item-editar">
							<a class="editbnt" href="?edit='.$row->id.'&tipo='.$row->tipo.'">Editar</a>
						</div>
					</div>
				');
			}
		}

	}

?>