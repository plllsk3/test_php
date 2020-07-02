<?php
// This is a model class for the student add form.
// It contains methods for inserting records to the db and
// checks for duplicates.

// Require DBConnection class.
require_once "DBConnection.php";

class Student
{
	private $lastname;
	private $firstname;
	private $midname;
	private $birthdate;
	private $class_id;
	private $errors = array();

	public function set_name($lastname, $firstname, $midname) {
		$this->lastname = $lastname;
		$this->firstname = $firstname;
		$this->midname = $midname;
	}

	// Method sets a student birthdate and checks whether
	// the birthdate is valid.
	public function set_birthdate($bday, $bmonth, $byear) {

		if( checkdate($bmonth, $bday, $byear) ) {
			$str_time = $byear.'-'.$bmonth.'-'.$bday;
			$timestamp = strtotime($str_time);

			$this->birthdate = date('Y-m-d', $timestamp);
		} else {
			$this->errors[] = 'Некорректная дата рождения!';
		}

	}

	public function set_class_id($class_id) {
		$this->class_id = $class_id;
	}

	// Method checks if there was any error
	// while inserting a student to the db.
	public function check_errors() {

		if (!empty($this->errors)) {
			return $this->errors;
		} else {
			return false;
		}
	}

	public function record_to_db() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "INSERT INTO students VALUES (NULL, :SLastName, :SFirstName, :SMidName, :SBirthDate, :CId)";

			$student = $pdo->prepare($query);
			$student->execute(['SLastName' => $this->lastname, 'SFirstName' => $this->firstname,
							 'SMidName' => $this->midname, 'SBirthDate' => $this->birthdate,
							 'CId' => (int)$this->class_id]);
		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}

	}

	// Method checks if a record already exists
	// in the db.
	public function check_record() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "SELECT * FROM students WHERE (SLastName = :SLastName AND SFirstName = :SFirstName AND SMidName = :SMidName AND SBirthDate = :SBirthDate)";

			$st = $pdo->prepare($query);
			$st->execute(['SLastName' => $this->lastname, 'SFirstName' => $this->firstname,
							 'SMidName' => $this->midname, 'SBirthDate' => $this->birthdate]);

			if( $st->fetch() ) {
				$this->errors[] = 'Такой ученик уже есть в списке!';
			}

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}
	}

}

?>