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
			if(array_key_exists('id', $_GET)) {
				$id =$_GET['id'];
				$name = $dbManager->getHouseNameById($id);
				$complex_name = $dbManager->getComplexNameByHouseId($id);
			}
			if(array_key_exists('name', $_POST)) {				
				$complex_id = $dbManager->getComplexIdByName($_POST['complex_name']);
				$sql = "UPDATE houses SET
					name = '".$_POST['name'].
					"', complex_id = '".$complex_id.
					"' WHERE id = ".$id;
				$dbManager->runQuery($sql);
				echo "<script>document.location.href = 'admin.php'</script>";
			
			}
		?>


		<div class = 'container'>
			<h2 class = 'display-4'>Изменить информацию про дом</h2>
			<p></p>
			<?php
				echo "<form action = 'edit_house.php?id=".$id."' method = 'POST'>
					<h5>Название:</h5>  <input type = 'text' class = 'form-control' name = 'name' value = '".$name."' />
					<h5>Комплекс:</h5>  
					<select name = 'complex_name' class = 'form-control'>
					"; 
				$complex_names = $dbManager->getAllComplexNamesAsArray();
				foreach($complex_names as $cur_complex_name) {
					echo "<option value = '".$cur_complex_name."' ".($complex_name == $cur_complex_name  ? "selected = 'selected'" : "").
					">".$cur_complex_name."</option>\n";
				}
				echo"</select>
					<p></p>
					<input type = 'submit' value = 'Изменить' class = 'btn btn-warning' />
					
				</form>";
			?>
		</div>
	</body>
</html>