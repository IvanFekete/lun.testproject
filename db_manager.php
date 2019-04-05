<?php
class DbManager {
	private $conn;
	
	function __construct() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "testproj_database";

		
		try {
			$this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,
					array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			  )); 
		}
		catch(PDOException $e) {
		
		}
	}

	function runQuery($sql) {
		try {
			$this->conn->exec($sql);
			$message = "Added successfully.";
		}
		catch(PDOException $e) {
			$message = "Error: " . $sql . "<br>" . $e->getMessage();
		}
		return $message;
	}

	function addComplex($name, $city) {
		$sql = "INSERT INTO complexes (name, city) VALUES ('".$name."', '".$city."')";
		return $this->runQuery($sql);
	}
	
	function addHouse($name, $complex_id) {
		$sql = "INSERT INTO houses (name, complex_id) VALUES ('".$name."', ".$complex_id.")";
		return $this->runQuery($sql);
	}
	function addFlat($house_id, $flat_type_id, $square, $price) {
		$sql = "INSERT INTO flats (house_id, flat_type_id, square, price) VALUES (".$house_id.",".$flat_type_id.",".$square.",".$price.")";
		return $this->runQuery($sql);
	}
	
	function runSelectQuery($sql) {
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_ASSOC);	
		return $stmt->fetchAll();
	}

	function getFieldBy($table_name, $field1, $field2, $value2) {
		$strVal = gettype($value2) == "integer" ? strval($value2) : "'".$value2."'";
		$sql = "SELECT ".$field1." FROM ".$table_name." WHERE ".$field2." = ".$strVal;
		
		$result = $this->runSelectQuery($sql);
		return $result[0][$field1];
	}

	function getComplexIdByName($complex_name) {
		return $this->getFieldBy("complexes", "id", "name", $complex_name);
	}


	function getHouseNameById($house_id) {
		return $this->getFieldBy("houses", "name", "id", $house_id);
	}


	function getComplexNameById($complex_id) {
		return $this->getFieldBy("complexes", "name", "id", $complex_id);
	}

	function getComplexCityByComplexName($complex_name) {
		return $this->getFieldBy("complexes", "city", "name", $complex_name);
	}

	function getFlatTypeById($id) {
		return $this->getFieldBy("flat_types", "name", "id", $id);
	}
	
	function getFlatIdByType($name) {
		return $this->getFieldBy("flat_types", "id", "name", $name);
	}


	function getComplexNameByHouseId($house_id) {
		$complex_id = $this->getFieldBy("houses", "complex_id", "id", $house_id);
		$complex_name = $this->getComplexNameById($complex_id);
		return $complex_name;
	}
	
	function getHouseIdByNameAndComplexId($house_name, $complex_id) {
		$sql = "SELECT id FROM houses WHERE name = '".$house_name."' AND complex_id = ".$complex_id;
		$result = $this->runSelectQuery($sql);
		return $result[0]['id'];
	}
	
	function getComplexNameByFlatId($id) {
		$house_id = $this->getFieldBy('flats', 'house_id', 'id', $id);
		$complex_id = $this->getFieldBy('houses', 'complex_id', 'id', $house_id);
		return $this->getComplexNameById($complex_id);
	}
	
	function getHouseNameByFlatId($id) {
		$house_id = $this->getFieldBy('flats', 'house_id', 'id', $id);
		return $this->getHouseNameById($house_id);
	}
	
	function getFlatTypeByFlatId($id) {
		$flat_type_id = $this->getFieldBy('flats', 'flat_type_id', 'id', $id);
		return $this->getFlatTypeById($flat_type_id);
	}
	
	function getFlatSquareById($id) {
		return $this->getFieldBy('flats', 'square', 'id', $id);
	}
	
	function getFlatPriceById($id) {
		return $this->getFieldBy('flats', 'price', 'id', $id);
	}
	
	function getAllCitiesAsArray() {
		$sql = "SELECT DISTINCT city FROM complexes";
		$response = $this->runSelectQuery($sql);		
		$result = [];
		foreach($response as $row) {
			array_push($result, $row['city']);
		}
		return $result;
	}
	
	function getAllComplexNamesAsArray() {
		$sql = "SELECT name FROM complexes";
		$response = $this->runSelectQuery($sql);		
		$result = [];
		foreach($response as $row) {
			array_push($result, $row['name']);
		}
		return $result;
	}
	
	function getAllFlatTypesAsArray() {
		$sql = "SELECT name FROM flat_types";
		$response = $this->runSelectQuery($sql);		
		$result = [];
		foreach($response as $row) {
			array_push($result, $row['name']);
		}
		return $result;
	}

	function selectAllFlats() {
		$sql = "SELECT * from flats";
		return $this->runSelectQuery($sql);
	}	
	
	function deleteFlat($id){
		$sql = 'DELETE FROM flats WHERE id = '.$id;
		return $this->runQuery($sql);
	}
	
	function deleteHouse($id){
		$sql = 'DELETE FROM houses WHERE id = '.$id;
		return $this->runQuery($sql);
	}
	
	function deleteComplex($id){
		$sql = 'DELETE FROM complexes WHERE id = '.$id;
		return $this->runQuery($sql);
	}
	
	function isFlatTypical($id) {
		$square = $this->getFlatSquareById($id);
		$price = $this->getFlatPriceById($id);
		$flat_type_id = $this->getFlatIdByType($this->getFlatTypeByFlatId($id));
		
		$house_id = $this->getFieldBy('flats', 'house_id', 'id', $id);
		$complex_id = $this->getFieldBy('houses', 'complex_id', 'id', $house_id);
		$sql = "SELECT id FROM 
			houses WHERE complex_id = ".$complex_id;
		$houses_id = $this->runSelectQuery($sql);
		foreach($houses_id as $cur_house_id) {
			$sql = "SELECT COUNT(*) FROM flats WHERE house_id = ".$cur_house_id['id']." AND flat_type_id = ".$flat_type_id.
			" AND square = ".$square." AND price = ".$price;
			$result = $this->runSelectQuery($sql);
			if($result[0]['COUNT(*)'] == 0) {
				return false;
			}
		}
		return true;

	}
	
}
	

?>