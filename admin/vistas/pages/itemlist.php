<?php 
	include('pages_functions/listitems.php');
	if($error != '') { echo '<div id="error">'.$error.'</div>'; } 
	if($advertencia != '') { echo '<div id="advertencia">'.$advertencia.'</div>'; } 
?>
<div class="itemlist--header">
	<div class="itemlist--item-nombre">Nombre</div>
	<div class="itemlist--item-tipo">Tipo</div>
	<div class="itemlist--item-cate">Categoria</div>
	<div class="itemlist--item-sede">Sede</div>
	<div class="itemlist--item-fecha">Fecha</div>
	<div class="itemlist--item-editar"></div>
</div>
<section class="itemlist">
	<?php allItems() ?>
</section>