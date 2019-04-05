<?php
	class DataFormatter {
		private static function isInteger($str) {
			for($i = 0; $i < strlen($str); $i++) {
				$c = $str[$i];
				if($c != ' ' && !(ctype_digit($c))) {
					return false;
				}
			}
			return (int)$str > 0;
		}
		
		private static function isFloat($str) {
			$was_point = false;
			for($i = 0; $i < strlen($str); $i++) {
				$c = $str[$i];
				if($c != ' ' && !(!$was_point && $c == '.') && !(ctype_digit($c))) {
					return false;
				}
			}
			return (float)$str > 0;
		}
		
		public static function toInt($str) {
			$new_str = "";
			for($i = 0; $i < strlen($str); $i++) {
				$c = $str[$i];
				if($c != ' ') {
					$new_str .= $c;
				}
			}
			return (int)$new_str;
		}
		
		public static function toFloat($str) {
			$new_str = "";
			for($i = 0; $i < strlen($str); $i++) {
				$c = $str[$i];
				if($c != ' ') {
					$new_str .= $c;
				}
			}
			return (float)$new_str;
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