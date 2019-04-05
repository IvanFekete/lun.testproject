2<!DOCTYPE html>
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
				$id = $_GET['id'];
				$name = $dbManager->getComplexNameById($id);
				$city = $dbManager->getComplexCityByComplexName($name);
			}
			
			if(array_key_exists('name', $_POST)) {
				$dbManager->runQuery("UPDATE complexes SET
					name = '".$_POST['name'].
					"', city = '".$_POST['city'].
					"' WHERE id = ".$id);
				echo '<script>
						document.location.href = "admin.php";
					  </script>';
			}
		?>

		<div class = 'container'>
			<h2 class = 'display-4'>Изменить информацию про новостройку</h2>
			<p></p>
			<?php
				
			echo"<form action = 'edit_complex.php?id=".$id."' method = 'POST'>
				<h5>Название:</h5>  <input type = 'text' class = 'form-control' name = 'name' value = '".$name."' />
				<h5>Город:</h5>  <input type = 'text' class = 'form-control' name = 'city' value = '".$city."'/>"
				?>
				<p></p>
				<input type = 'submit' value = 'Изменить' class = 'btn btn-warning' />
				
			</form>
		</div>
	</body>
</html>