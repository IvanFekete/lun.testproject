<?php
	class DataFormatter {
		public static function isInteger($str) {
			for($i = 0; $i < strlen($str); $i++) {
				$c = $str[$i];
				if($c != ' ' && !(ctype_digit($c))) {
					return false;
				}
			}
			return (int)$str > 0;
		}
		
		public static function isFloat($str) {
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
	}
?>