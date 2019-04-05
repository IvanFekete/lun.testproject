
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
		<div class = 'container'>
			<h2 class = 'display-4'>Добавить квартиру</h2>
			<p></p>
			<form action = 'add_new_flat_next.php' method = 'POST'>
				<h5>Комплекс:</h5>  
				<select name = 'complex_name' class = 'form-control'>
					<?php 
						include 'db_manager.php';
						$dbManager = new DbManager();
						$complex_names = $dbManager->getAllComplexNamesAsArray();
						foreach($complex_names as $complex_name) {
							echo "<option value = '".$complex_name."'>".$complex_name."</option>\n";
						}
					?>
				</select>
				
				<p></p>
				<input type = 'submit' value = 'Далее' class = 'btn btn-success' />
				
			</form>
		</div>
	</body>
</html>