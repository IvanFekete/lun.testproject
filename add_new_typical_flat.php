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
			include 'data_formats.php';
			include 'error_messages.php';
			$dbManager = new DbManager();
			
			
			if(array_key_exists('complex_name', $_POST)) {
				$error_message = ErrorMessages::getMessage($_POST['square'], $_POST['price']);
				if($error_message != '') {
					echo $error_message;
				}
				else {
					$complex_name = $_POST['complex_name'];
					$complex_id = $dbManager->getComplexIdByName($complex_name);
					$flat_type = $_POST['flat_type'];
					$flat_type_id = $dbManager->getFlatIdByType($flat_type);
					$square = DataFormatter::toFloat($_POST['square']);
					$price = DataFormatter::toInt($_POST['price']);
					if(!in_array('all', $_POST['price_type'])) {
						$price = (int) $price * $square;
					}
					$house_names = $dbManager->runSelectQuery("SELECT name FROM houses WHERE complex_id = '".$complex_id."'");
					$flats_number = (int) $_POST['flats_number'];
					foreach($house_names as $row) {
						$house_name = $row['name'];
						$house_id = $dbManager->getHouseIdByNameAndComplexId($house_name, $complex_id);
						for($i = 0; $i < $flats_number; $i++) {
							$dbManager->addFlat($house_id, $flat_type_id, $square, $price);
						}
					}
					header('Location: admin.php');
				}
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Добавить типовую квартиру</h2>
			<p></p>
			<form action = 'add_new_typical_flat.php' method = 'POST'>
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>
					<?php 
						$complex_names = $dbManager->getAllComplexNamesAsArray();
						foreach($complex_names as $complex_name) {
							echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
						}
					?>
				</select>
				
				<h5>Количество комнат:</h5>  
				<select name = 'flat_type' class = 'form-control'>
					<?php 
						$flat_types = $dbManager->getAllFlatTypesAsArray();
						foreach($flat_types as $flat_type) {
							echo "<option value = '".$flat_type."'>".$flat_type."</option>\n";
						}
					
					?>
				</select>
					
				<h5>Площадь(кв. м):</h5>  <input type = 'text' class = 'form-control' name = 'square' />
				<h5>Цена указана:</h5>
				<p>
					За кв.м   <input type = 'radio' name = 'price_type[]' value = 'for_squared_meter' checked = 'true'/>
					За всю квартиру <input type = 'radio' name = 'price_type[]' value = 'all' />
				</p>
				<h5>Цена(грн):</h5>  <input type = 'text' class = 'form-control' name = 'price' />
				<h5>Количество:</h5>  
				
				<select name = 'flats_number' class = 'form-control'>
					<option value = "1">1</option>
					<option value = "2">2</option>
					<option value = "3">3</option>
					<option value = "4">4</option>
					<option value = "5">5</option>
					<option value = "6">6</option>
					<option value = "7">7</option>
					<option value = "8">8</option>
					<option value = "9">9</option>
					<option value = "10">10</option>
				</select>
				
				<p></p>
				<input type = 'submit' value = 'Добавить' class = 'btn btn-success' />
				
			</form>
		</div>
	</body>
</html>