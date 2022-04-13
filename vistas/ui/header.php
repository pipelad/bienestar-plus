<?php
	function categMenu() {
		$con = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROM `categoria`');
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('<li><a href="?categ='.$row->id.'">'.$row->nombre.'</a></li>');
			}
		}
	}
?>
<header>
	<div class="logo-area">
		<div class="logo">
			<img src="images/base/logo_usta_small.svg" alt="Universidad Santo Tomás Sede Principal">
		</div>
		<div class="logo">
			<img src="images/base/logo_bp.png" alt="Bienestar plus">
		</div>
	</div>
	<nav id="main-nav">
		<ul>
			<li><a href="./">incio</a></li>
			<li id="catsub-over"><a>categorías</a>
				<ul id="catsub" class="submenu">
					<?php categMenu() ?>
				</ul>
			</li>
			<li><a href="?concurso">concurso</a></li>
			<li><a href="?contacto">contacto</a></li>
		</ul>
	</nav>
</header>