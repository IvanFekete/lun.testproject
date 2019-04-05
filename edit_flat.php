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
			
			if(array_key_exists('flat_id', $_POST)) {
				$id = $_POST['flat_id'];
				$complex_name = $dbManager->getComplexNameByFlatId($id);
				$house_name = $dbManager->getHouseNameByFlatId($id);
				$flat_type = $dbManager->getFlatTypeByFlatId($id);
				$square = $dbManager->getFlatSquareById($id);
				$price = $dbManager->getFlatPriceById($id);
			}
			else {
				echo ErrorMessages::getUnexpectedErrorMessage();
			}
			
			
			if(array_key_exists('house_name', $_POST)) {
				$error_message = ErrorMessages::getMessage($_POST['square'], $_POST['price']);
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
					$sql = "UPDATE flats SET
						house_id = ".$house_id.
						", flat_type_id = ".$flat_type_id.
						", square = ".$square.
						", price = ".$price.
						" WHERE id = ".$id;
					
					$dbManager->runQuery($sql);
					header('Location: admin.php');
				}
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Изменить информацию про квартиру</h2>
			<p></p>
			<?php 
			echo "
			<form action = 'edit_flat.php' method = 'POST'>
				<input type = 'hidden' name = 'flat_id' value = '".$id."' />
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>";
						echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
				echo "
				</select>
				
				<h5>Дом:</h5>  
				<select name = 'house_name' class = 'form-control'>
					
							<option value = '".$house_name."'>".$house_name."</option>\n;
						
					
					
				</select>
				
				<h5>Количество комнат:</h5>  
				<select name = 'flat_type' class = 'form-control'>";
						$flat_types = $dbManager->getAllFlatTypesAsArray();
						foreach($flat_types as $cur_flat_type) {
							echo "<option value = '".$cur_flat_type."' ".
							($flat_type == $cur_flat_type  ? "selected = 'selected'" : "").">".$cur_flat_type."</option>\n";
						}
				echo "
				</select>
					
				<h5>Площадь(кв. м):</h5>  <input type = 'text' class = 'form-control' name = 'square' value = '".$square."'/>
				<h5>Цена(за всю квартиру, грн):</h5>  <input type = 'text' class = 'form-control' name = 'price' value = '".$price."'/>
				
				<p></p>
				<input type = 'submit' value = 'Изменить' class = 'btn btn-warning' />
				
			</form>";
			?>
		</div>
	</body>
</html>