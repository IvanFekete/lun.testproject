<?php
	include 'db_manager.php';
	$dbManager = new DbManager();
	
	if(array_key_exists('delete_complexes', $_POST)) {
		$to_delete = $_POST['delete_complexes'];
		foreach($to_delete as $complex_id) {
			$dbManager->deleteComplex($complex_id);
		}
	}

?>
<script>
	document.location.href = "admin.php";
</script>