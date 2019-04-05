<!DOCTYPE html>
<html lang = 'ru'>
	<head>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	
		<meta charset = 'utf-8' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Новостройки:Админ</title>
	</head>
	<body>
		<?php
			include 'db_manager.php';
			include 'error_messages.php';
			$dbManager = new DbManager();
			
			if(array_key_exists('id', $_POST)) {
				$id = $_POST['id'];
				
				if($id == 'delete_complex') {
					if(array_key_exists('delete_complexes', $_POST)) {
						$to_delete = $_POST['delete_complexes'];
						foreach($to_delete as $complex_id) {
							$dbManager->deleteComplex($complex_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
					
				if($id == 'delete_flat') {
					if(array_key_exists('delete_flats', $_POST)) {
						$to_delete = $_POST['delete_flats'];
						foreach($to_delete as $flat_id) {
							$dbManager->deleteFlat($flat_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'delete_house') {
					if(array_key_exists('delete_houses', $_POST)) {
						$to_delete = $_POST['delete_houses'];
						foreach($to_delete as $house_id) {
							$dbManager->deleteHouse($house_id);
						}
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'add_new_complex') {
					if(array_key_exists('name', $_POST) && array_key_exists('name', $_POST)) {
						$dbManager->addComplex($_POST['name'], $_POST['city']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'add_new_house') {
					if(array_key_exists('name', $_POST) && array_key_exists('complex_name', $_POST)) {
						$complex_name = $_POST['complex_name'];				
						$complex_id = $dbManager->getComplexIdByName($complex_name);
						$dbManager->addHouse($_POST['name'], $complex_id);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'edit_complex') {					
					if(array_key_exists('name', $_POST) && array_key_exists('city', $_POST) && array_key_exists('id', $_POST)) {
						$dbManager->runQuery("UPDATE complexes SET
							name = '".$_POST['name'].
							"', city = '".$_POST['city'].
							"' WHERE id = ".$_POST['complex_id']);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
				if($id == 'edit_house') {
					if(array_key_exists('name', $_POST) && array_key_exists('complex_name', $_POST) && array_key_exists('id', $_POST)) {				
						$complex_id = $dbManager->getComplexIdByName($_POST['complex_name']);
						$sql = "UPDATE houses SET
							name = '".$_POST['name'].
							"', complex_id = '".$complex_id.
							"' WHERE id = ".$_POST['house_id'];
						$dbManager->runQuery($sql);
					}
					else {
						echo ErrorMessages::getUnexpectedErrorMessage();
					}
				}
				
			}
			
			function getCol($s) {
				return "<td>".$s."</td>";
			}
		?>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'client.php'>Клиент</a> или на <a href = 'index.php'>главную</a>.</h1>
			</div>


			<h2> ЖК </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_complex.php"' />
			
			<form id = "delete_complex" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_complex" />
			</form>
			<script>
				$("#delete_complex").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные новостройки?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Город</td>
					<td>Название</td>
					<td>Изменить</td>
					<td><input form = "delete_complex" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$sql = "SELECT * FROM complexes";
				
					$all = $dbManager->runSelectQuery($sql);
					
					
					foreach($all as $row) {
						$name = $row['name'];
						$city = $row['city'];
						$update_button = "
						<form action = 'edit_complex.php' method = 'POST'>
							<input type = 'hidden' name = 'complex_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_complex' type = 'checkbox' name = 'delete_complexes[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($city).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			
		
			<h2> Дома </h2>
			
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_house.php"' />
			<form id = "delete_house" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_house" />
			</form>
			<script>
				$("#delete_house").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные дома?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Дом</td>
					<td>Комплекс</td>
					<td>Изменить</td>
					<td><input form = "delete_house" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$sql = "SELECT * FROM houses ORDER BY complex_id";
				
					$all = $dbManager->runSelectQuery($sql);
					
					foreach($all as $row) {
						$name = $row['name'];
						$complex_name = $dbManager->getComplexNameByHouseId($row['id']);;
						$update_button = "
						<form action = 'edit_house.php' method = 'POST'>
							<input type = 'hidden' name = 'house_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_house' type = 'checkbox' name = 'delete_houses[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($complex_name).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
		
		
			<h2> Квартиры </h2>
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_flat.php"' />
			<input type = 'button' class = 'btn btn-success' value = 'Добавить типовую квартиру' 
				onclick = 'document.location.href = "add_new_typical_flat.php"' />
			
			<form id = "delete_flat" action = "admin.php" method = "POST">
				<input type = "hidden" name = "id" value = "delete_flat" />
			</form>
			<script>
				$("#delete_flat").submit(function() {
					return confirm("Вы действительно хотите удалить выбранные квартиры?");
				});
			</script>
			<table class = "table table-striped">
				<tr>
					<td>Город</td>
					<td>ЖК</td>
					<td>Название дома</td>
					<td>Количество комнат</td>
					<td>Площадь</td>
					<td>Цена</td>
					<td>Изменить</td>
					<td><input form = "delete_flat" type = "submit" class = "btn btn-danger" value = "Удалить" /></td>
				</tr>
				<?php
					$sql = "SELECT * FROM flats ORDER BY flat_type_id";
				
					$allFlats = $dbManager->runSelectQuery($sql);
					
					
					foreach($allFlats as $row) {
						$complex_name = $dbManager->getComplexNameByHouseId($row['house_id']);
						$house_name = $dbManager->getHouseNameById($row['house_id']);
						$complex_city = $dbManager->getComplexCityByComplexName($complex_name);
						$flat_type = $dbManager->getFlatTypeById($row['flat_type_id']);
						$square = $row['square'];
						$price = $row['price'];
						$update_button = "
						<form action = 'edit_flat.php' method = 'POST'>
							<input type = 'hidden' name = 'flat_id' value = '".$row['id']."' />
							<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' />
						</form>";
						$delete_checkbox = "<input form = 'delete_flat' type = 'checkbox' name = 'delete_flats[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($complex_city).getCol($complex_name).getCol($house_name).
							getCol($flat_type).getCol($square."  кв.м").getCol($price." грн").
							getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			</form>
		</div>
	</body>
</html>