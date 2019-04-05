<?php
	include 'db_manager.php';
	$dbManager = new DbManager();
	
	if(array_key_exists('delete_houses', $_POST)) {
		$to_delete = $_POST['delete_houses'];
		foreach($to_delete as $house_id) {
			$dbManager->deleteHouse($house_id);
		}
	}

?>
<script>
	document.location.href = "admin.php";
</script>