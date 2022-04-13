<?php
	function bannerItems() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `banner` ORDER BY `id` DESC');
		if($sql->execute()) {
			// Determina si hay items existentes y los muestra
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('
					<div class="banner--item">
						<img src="'.substr($row->url, 3).'">
					</div>
				');
			}
		}
	}
?>

<div id="banner" class="banner" >
	<div id="banner--controls-left" class="banner--controls">
		<img src="images/base/flecha-left.svg">
	</div>
	<div class="banner--container">
		<div id="banner-items" class="banner--container-items">
			<?php bannerItems() ?>
		</div>
	</div>
	<div id="banner--controls-right" class="banner--controls">
		<img src="images/base/flecha-right.svg">
	</div>
</div>


<script type="text/javascript" src="js/componentes/banner.js"></script>