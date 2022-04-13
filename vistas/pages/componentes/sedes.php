<?php
	function allSedes() {
		$con = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$sql = $con->prepare('SELECT * FROm `sedes`');
		if($sql->execute()) {
			while($row = $sql->fetch(PDO::FETCH_OBJ)) {
				echo('
					<a class="sedes--item sede_'.$row->id.'" href="?sede='.$row->id.'">
						<div class="bp_desplazo">
							<div class="sedes--item-foto">
								<img src="images/base/sedes/empty.png" data-src="images/base/sedes/sede_'.$row->id.'.gif" alr="'.$row->nombre.'">
							</div>
							<div class="sedes--item-gif">
								<img src="images/base/sedes/sede_'.$row->id.'.gif" >
							</div>
							<div class="sedes--item-name">
								'.$row->nombre.'
							</div>
						</div>
					</a>
				');
			}
		}
	}
?>

<div class="sedes">
	<div></div>
	<div class="sedes--container">
		<div id="sedes--items" class="sedes--items">
			<?php allSedes() ?>
		</div>
	</div>
	<div></div>
</div>