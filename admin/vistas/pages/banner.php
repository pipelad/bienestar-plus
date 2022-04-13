<?php 
	if($error != '') { echo '<div id="error">'.$error.'</div>'; } 
	if($advertencia != '') { echo '<div id="advertencia">'.$advertencia.'</div>'; } 
?>
<div class="banner">
<?php
	if(array_key_exists('editbanner', $_GET)) {
?>
	<h2>Editar el Item</h2>
<?php
	editItem();
	}
	else {
?>
	<h2>Editar el banner</h2>
	<div class="banner--items">
		<?php bannerItems() ?>
	</div>
	<form method="POST" class="banner--form" id="bannerform" enctype="multipart/form-data">
		<div id="banner--data">
			<div class="row">
				<label for="foto">Foto</label>
				<input type="file" class="bannerfoto" name="foto[]" accept="image/*">
			</div>
			<input hidden value="new" name="tipo">
		</div>
		<div id="addgaleryitem">+</div>
		<div class="submit">
			<button id="submit">Guardar</button>
			<a class="cancelar" href="./">Cancelar</a>
		</div>
	</form>
<?php	
	}
?>
	<script type="text/javascript" src="js/banner.js"></script>
</div>