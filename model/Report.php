<?php
// This is a model class which handles the report
// requests. It contains methods for a request manipulation.

// Require DBConnection class.
require_once "DBConnection.php";

class Report
{
	private $code;
	private $rep_id;
	private $report_list;

	public function __construct($code, $rep_id ) {
		$this->code = $code;
		$this->rep_id = $rep_id;
	}

	// According to the code of the request which was sent
	// by browser, it executes propper private method. Each private method
	// extracts propper data from db, accordin to $rep_id property.
	public function load_report() {
		
		switch($this->code) {

			case 'yn':
			$this->load_youngest_student();
			break;

			case 'ol':
			$this->load_oldest_student();
			break;

			case 'cl':
			$this->load_students_in_class();
			break;

			case 'bd':
			$this->load_students_by_birthday();
			break;
		}

	}

	public function get_report() {
		return $this->report_list;
	}

	private function load_youngest_student(){
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "SELECT * FROM students WHERE SBirthDate = (SELECT MAX(SBirthDate) FROM students)";

			$st = $pdo->query($query);

			$student = $st->fetch();

			$cl_id = $student['CId'];

			$query_cl = "SELECT * FROM classes WHERE CId= :CId";

			$cl = $pdo->prepare($query_cl);

			$cl->execute(['CId' => $cl_id]);

			$cl = $cl->fetch();

			$name = $student['SLastName'].' '.$student['SFirstName'].' '.$student['SMidName'];
			$birthdate = $student['SBirthDate'];
			$st_id = $student['SId'];
			$class = $cl['CLevel'].$cl['CLetter'];

			$this->report_list[] = ['name' => $name, 'birthdate' => $birthdate, 'class' => $class, 'id' => $st_id];

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}

	}

	private function load_oldest_student(){
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "SELECT * FROM students WHERE SBirthDate = (SELECT MIN(SBirthDate) FROM students)";

			$st = $pdo->query($query);

			$student = $st->fetch();

			$cl_id = $student['CId'];

			$query_cl = "SELECT * FROM classes WHERE CId= :CId";

			$cl = $pdo->prepare($query_cl);

			$cl->execute(['CId' => $cl_id]);

			$cl = $cl->fetch();

			$name = $student['SLastName'].' '.$student['SFirstName'].' '.$student['SMidName'];
			$birthdate = $student['SBirthDate'];
			$st_id = $student['SId'];
			$class = $cl['CLevel'].$cl['CLetter'];

			$this->report_list[] = ['name' => $name, 'birthdate' => $birthdate, 'class' => $class, 'id' => $st_id];

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}

	}

	private function load_students_in_class() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {

			$query = "SELECT * FROM students WHERE CId = (SELECT CId FROM classes WHERE CLevel = :CLevel AND CLetter = 'А')
						 OR CId = (SELECT CId FROM classes WHERE CLevel = :CLevel AND CLetter = 'Б')
						 ORDER BY SLastName, SFirstName, SMidName";

			$st = $pdo->prepare($query);
			$st->execute(['CLevel' => $this->rep_id]);

			while( $student = $st->fetch() ) {

				$query_cl = "SELECT * FROM classes WHERE CId= :CId";

				$cl = $pdo->prepare($query_cl);
				$cl->execute(['CId' => $student['CId']]);

				$cl = $cl->fetch();

				$name = $student['SLastName'].' '.$student['SFirstName'].' '.$student['SMidName'];
				$birthdate = $student['SBirthDate'];
				$st_id = $student['SId'];
				$class = $cl['CLevel'].$cl['CLetter'];

				$this->report_list[] = ['name' => $name, 'birthdate' => $birthdate, 'class' => $class, 'id' => $st_id];
			}

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}
	}

	private function load_students_by_birthday() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "SELECT * FROM students WHERE MONTH(SBirthDate) = :month
			ORDER BY SLastName, SFirstName, SMidName";

			$st = $pdo->prepare($query);
			$st->execute(['month' => $this->rep_id]);

			while( $student = $st->fetch() ) {

				$query_cl = "SELECT * FROM classes WHERE CId= :CId";

				$cl = $pdo->prepare($query_cl);
				$cl->execute(['CId' => $student['CId']]);
				$cl = $cl->fetch();

				$name = $student['SLastName'].' '.$student['SFirstName'].' '.$student['SMidName'];
				$birthdate = $student['SBirthDate'];
				$st_id = $student['SId'];
				$class = $cl['CLevel'].$cl['CLetter'];

				$this->report_list[] = ['name' => $name, 'birthdate' => $birthdate, 'class' => $class, 'id' => $st_id];
			}

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}
	}
}

?>