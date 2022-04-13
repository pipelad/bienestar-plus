<form method="POST" class="addform" id="addform" action="./" enctype="multipart/form-data">
	<h2>Añadir un Item</h2>
	<div class="addform--info">
		<div class="basic-data">
			<div class="row">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" required>
			</div>
			<div class="row">
				<label for="categoria">Categoria</label>
				<select name="categoria" id="cate" required>
					<option value="" hidden selected disabled>Seleccione Una Opción</option>
					<?php categorias(); ?>
				</select>
			</div>
			<div class="row">
				<label for="sede">Sede</label>
				<select name="sede" id="cate" required>
					<option value="" hidden selected disabled>Seleccione Una Opción</option>
					<?php sedes(); ?>
				</select>
			</div>
			<div class="row">
				<label for="fecha">Fecha</label>
				<input type="date" id="fecha" name="fecha" required>
			</div>
			<div class="row">
				<label for="tipo">Tipo</label>
				<select name="tipo" id="tipo">
					<option value="video" selected>Video</option>
					<option value="fotos">Fotos</option>
				</select>
			</div>
		</div>
		<div class="content-data">
			<div id="video" class="enabled">
				<h3>Video</h3>
				<div class="row">
					<label for="url-video">url</label>
					<input type="text" id="video-url" name="videourl">
				</div>
				<div class="row">
					<label for="miniatura">Miniatura</label>
					<input type="file" id="video-min" name="miniatura" accept="image/*">
				</div>	
			</div>
			<div id="galeria" class="disabled">
				<h3>Galería</h3>
				<div id="galeria--items">
					<div class="row"><div class="galeria--items-item"><label for="img_gal_1">Foto:</label><input disabled name="img_gal[1]" type="file" accept="image/*"></div></div>
				</div>
				<div id="addgaleryitem">+</div>
			</div>
		</div>
	</div>
	<div class="addform--submit">
		<input hidden name="submit" value="1">
		<button>Guardar</button>
		<a href="./" id="cancelform">cancelar</a>
	</div>
</form>

<script type="text/javascript" src="js/formulario.js"></script>