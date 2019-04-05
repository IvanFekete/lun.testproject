<?php
	include 'db_manager.php';
	$dbManager = new DbManager();
	
	if(array_key_exists('delete_flats', $_POST)) {
		$to_delete = $_POST['delete_flats'];
		foreach($to_delete as $flat_id) {
			$dbManager->deleteFlat($flat_id);
		}
	}

?>
<script>
	document.location.href = "admin.php";
</script>