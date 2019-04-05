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
			$dbManager = new DbManager();
			
			
			if(array_key_exists('house_name', $_POST)) {
				$error_message = DataFormatter::getMessage($_POST['square'], $_POST['price']);
				if($error_message != '') {
					echo $error_message;
				}
				else {
					$complex_name = $_POST['complex_name'];
					$complex_id = $dbManager->getComplexIdByName($complex_name);
					$house_name = $_POST['house_name'];
					$house_id = $dbManager->getHouseIdByNameAndComplexId($house_name, $complex_id);
					$flat_type = $_POST['flat_type'];
					$flat_type_id = $dbManager->getFlatIdByType($flat_type);
					$square = DataFormatter::toFloat($_POST['square']);
					$price = DataFormatter::toInt($_POST['price']);
					if(!in_array('all', $_POST['price_type'])) {
						$price = (int) $price * $square;
					}
					$dbManager->addFlat($house_id, $flat_type_id, $square, $price);
					echo "<script>document.location.href = 'admin.php'</script>";
				}
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Добавить квартиру</h2>
			<p></p>
			<form action = 'add_new_flat_next.php' method = 'POST'>
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>
					<?php 
						$complex_name = $_POST['complex_name'];
						echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
					?>
				</select>
				
				<h5>Дом:</h5>  
				<select name = 'house_name' class = 'form-control'>
					<?php 
						$complex_id = $dbManager->getComplexIdByName($complex_name);
						$complex_houses = $dbManager->runSelectQuery("SELECT name FROM houses WHERE complex_id = ".$complex_id);
						foreach($complex_houses as $row) {
							echo "<option value = '".$row['name']."'>".$row['name']."</option>\n";
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
				
				<p></p>
				<input type = 'submit' value = 'Добавить' class = 'btn btn-success' />
				
			</form>
		</div>
	</body>
</html>