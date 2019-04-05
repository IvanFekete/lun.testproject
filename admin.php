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
			$dbManager = new DbManager();
			
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
			
			<form id = "delete_complex" action = "delete_complex.php" method = "POST"></form>
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
						$update_button = " <input type = 'submit' class = 'btn btn-warning' value = 'Изменить' 
						onclick = 'document.location.href = \"edit_complex.php?id=".$row['id']."\"'/>";
						$delete_checkbox = "<input form = 'delete_complex' type = 'checkbox' name = 'delete_complexes[]' value = '".$row['id']."'/>";
						echo "<tr>".getCol($name).getCol($city).getCol($update_button).getCol($delete_checkbox)."</tr>";
					}
				?>
			</table>
			
		
			<h2> Дома </h2>
			
			
			<input type = 'button' class = 'btn btn-success' value = 'Добавить' 
				onclick = 'document.location.href = "add_new_house.php"' />
			<form id = "delete_house" action = "delete_house.php" method = "POST"></form>
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
						$update_button = "<input type = 'submit' class = 'btn btn-warning' value = 'Изменить'
						onclick = 'document.location.href = \"edit_house.php?id=".$row['id']."\"'/>";
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
			
			<form id = "delete_flat" action = "delete_flat.php" method = "POST"></form>
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
						$update_button = "<input type = 'submit' class = 'btn btn-warning' value = 'Изменить' 
						onclick = 'document.location.href = \"edit_flat.php?id=".$row['id']."\"'/>";
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