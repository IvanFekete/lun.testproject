<?php
	include 'db_manager.php';
	$dbManager = new DbManager();
?>
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
		
		<title>Новостройки:Клиент</title>
	</head>
	<body>
		<div class = 'container'>
			<div class = 'row'>
				<h1 class = 'display-4'>Перейти на <a href = 'admin.php'>Админ</a> или на <a href = 'index.php'>главную</a>.</h1>
			</div>
			<div class = 'row'>
				<div class = 'col-sm-4'>				
					<form action = 'client.php' method = 'GET'>
						<h2>Город:</h2>
						
						<div class = 'container'>
							<?php
								$cities = $dbManager->getAllCitiesAsArray();
								foreach($cities as $city) {
									$checked = "";
									if(array_key_exists('city', $_GET) && in_array($city, $_GET['city'])) {
										$checked = "checked";
									}
									echo "<p><input type = 'checkbox' name = 'city[]' value = '".
										$city."' ".$checked." />".$city."</p>";
								}
							?>
						</div>
						
						<h2>Количество комнат:</h2>
						
						<div class = 'container'>
							<?php						
								$flat_types = $dbManager->getAllFlatTypesAsArray();
								foreach($flat_types as $flat_type) {
									$checked = "";
									if(array_key_exists('flat_type', $_GET) && in_array($flat_type, $_GET['flat_type'])) {
										$checked = "checked";
									}
									echo "<p><input type = 'checkbox' name = 'flat_type[]' value = '".
									$flat_type."' ".$checked."/>".$flat_type."</p>";
								}
							?>
						</div>
						
						<input type = 'submit' class = 'btn btn-primary' value = 'Фильтр' />
					</form>
				</div>
				
				<div class = 'col-sm-8'>
					<table class = "table table-striped">
						<tr>
							<td>Город</td>
							<td>ЖК</td>
							<td>Название дома</td>
							<td>Количество комнат</td>
							<td>Площадь</td>
							<td>Цена</td>
						</tr>
						<?php
							$sql = "SELECT * FROM flats";
							$city_statement = "";
							$flat_type_statement = "";
							
							if(array_key_exists('city', $_GET)) {
								foreach($_GET['city'] as $city_name) {
									if($city_statement != '') {
										$city_statement .= " OR ";
									}
									$city_statement .= "complexes.city = '".$city_name."'";
								}
							}
							if(array_key_exists('flat_type', $_GET)) {								
								foreach($_GET['flat_type'] as $flat_type) {
									$flat_type_id = $dbManager->getFlatIdByType($flat_type);
									if($flat_type_statement != '') {
										$flat_type_statement .= " OR ";
									}
									$flat_type_statement .= "flats.flat_type_id = ".$flat_type_id;
								}
							}
							
							
							if($city_statement != '') {
								$sql = "SELECT flats.id, flats.house_id, flats.flat_type_id, flats.square, flats.price 
								FROM (complexes INNER JOIN 
								(houses INNER JOIN flats ON flats.house_id = houses.id) 
									ON complexes.id = houses.complex_id) WHERE (".$city_statement.")";
								if($flat_type_statement != '') {
									$sql .= " AND ".$flat_type_statement;
								}
								
								$sql .= " ORDER BY flats.price";
							}
							else if($flat_type_statement != '') {
								$sql .= " WHERE (";
								if($flat_type_statement != '') {
									$sql .= $flat_type_statement.")";
								}
								$sql .= " ORDER BY price";
							}
							
							
						
						
							$allFlats = $dbManager->runSelectQuery($sql);
							for($i = 0; $i < count($allFlats); $i++) {
								$allFlats[$i]['typical'] = $dbManager->isFlatTypical($allFlats[$i]['id']);
							}
							function getCol($s) {
								return "<td>".$s."</td>";
							}
							function compareFlats($u, $v) {
								if($u['price'] != $v['price']) {
									return $u['price'] < $v['price'] ? -1 : 1;
								}
								$typicalU = $u['typical'];
								$typicalV = $v['typical'];
								if($typicalU != $typicalV) {
									return $typicalU ? -1 : 1;
								}
								return 0;
							}
							usort($allFlats, "compareFlats");
							
							foreach($allFlats as $row) {
								$dbManager->isFlatTypical($row['id']);
								$complex_name = $dbManager->getComplexNameByHouseId($row['house_id']);
								$house_name = $dbManager->getHouseNameById($row['house_id']);
								$complex_city = $dbManager->getComplexCityByComplexName($complex_name);
								$flat_type = $dbManager->getFlatTypeById($row['flat_type_id']);
								$square = $row['square'];
								$price = $row['price'];
								echo "<tr>".getCol($complex_city).getCol($complex_name).getCol($house_name).
									getCol($flat_type).getCol($square."  кв.м").getCol($price." грн")."</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>