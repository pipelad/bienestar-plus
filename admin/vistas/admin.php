<?php 
	include('pages/pages_functions/additem.php');
	include('pages/pages_functions/banner-update.php');
	include('pages/pages_functions/edititem.php');
?>
<div class="admin">
	<?php include('ui/header.php') ?>
	<main class="admin--body">
		<?php
			if(array_key_exists("add", $_GET)) {
				include('pages/itemadd.php'); 
			}
			else if(array_key_exists("banner", $_GET) || array_key_exists("editbanner", $_GET)) {
				include('pages/banner.php'); 
			}
			else if(array_key_exists("edit", $_GET)) {
				include('pages/itemedit.php'); 
			}
			else {
				include('pages/itemlist.php'); 
			}
		?>
	</main>
</div>