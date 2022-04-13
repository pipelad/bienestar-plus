<main class="login">
	<form method="POST" class="login--form">
		<div class="login--form-row transparent">
			<img src="../images/base/logo_bp.png" alt="Bienestar plus" class="logo">
		</div>
		<?php if($error != "") { echo '<div id="error">'.$error.'</div>'; } ?>
		<div class="login--form-row">
			<label for="user">Usuario:</label><input type="text" name="usuario">
		</div>
		<div class="login--form-row">
			<label for="user">Contraseña:</label><input type="password" name="pass">
		</div>
		<div class="login--form-row submit">
			<input hidden name="login" value="1">
			<button>Ingresar</button>
		</div>
	</form>
</main>