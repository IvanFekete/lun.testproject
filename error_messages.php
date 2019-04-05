<?php
	class ErrorMessages {
		public static function getUnexpectedErrorMessage() {
			return '<div class="alert alert-danger">
						<strong>Ошибка!</strong> Возникла непредвиденная ошибка. Перейти на 
							<a href = "admin.php">Админ</a> или <a href = "client.php">Клиент</a>.
					</div>';
		}
		
		public static function getMessage($square, $price) {
			$msg = "";
			if(!DataFormatter::isInteger($price)) {
				$msg .= '<div class="alert alert-danger">
						<strong>Ошибка!</strong> Цена должна быть целым положительным числом.
					</div>';
			}
			if(!DataFormatter::isFloat($square)) {
				$msg .= '<div class="alert alert-danger">
						<strong>Ошибка!</strong> Площадь должна быть положительным числом.
					</div>';
			}
			return $msg;
		}
	}
?>