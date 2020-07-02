<?php
// This is a model class for handling data for
// the student list section on the page. It gets
// required students from the db, according to the
// chosen class. It also gets the information about
// all existing classes in the db and deletes a chosen student.

// Require DBConnection class.
require_once "DBConnection.php";

class Student_list
{
	private $classes = array();
	private $students = array();

	// Method extracts the data about all classes in db.
	public function set_classes() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "SELECT * FROM classes ORDER BY CLevel, CLetter";

			$cl = $pdo->query($query);
			$classes = $cl->fetchAll();

			foreach($classes as $class) {
				$class_id = $class['CId'];
				$class_name = $class['CLevel'].$class['CLetter'];
				$this->classes[$class_id] = $class_name;
			}
		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}
	}

	// Method extracts the students data for a chosen class.
	public function set_students($cl_id) {
		$db = new DBConnection();
		$pdo = $db->pdo;

		$cl_id = $cl_id;

		try {
			$query = "SELECT * FROM students WHERE CId= :CId ORDER BY SLastName, SFirstName, SMidName";

			$st = $pdo->prepare($query);
			$st->execute(['CId' => $cl_id]);

			$query_cl = "SELECT * FROM classes WHERE CId= :CId";

			$cl = $pdo->prepare($query_cl);
			$cl->execute(['CId' => $cl_id]);

			$cl_name = $cl->fetch();
			$cl_name = $cl_name['CLevel'].$cl_name['CLetter'];

			while($student = $st->fetch()) {
				$name = $student['SLastName'].' '.$student['SFirstName'].' '.$student['SMidName'];
				$class = $cl_name;
				$birthdate = $student['SBirthDate'];
				$id = $student['SId'];

				$this->students[] = ['name' => $name, 'class' => $class, 'birthdate' => $birthdate, 'id' => $id];
			}
		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}
	}

	public function get_classes() {
		return $this->classes;
	}

	public function get_students() {
		return $this->students;
	}

	// Method deletes a chosen student from db.
	public function delete_student($st_id) {
		$db = new DBConnection();
		$pdo = $db->pdo;

		try {
			$query = "DELETE FROM students WHERE SId= :SId";

			$st = $pdo->prepare($query);
			$st->execute( ['SId' => $st_id] );

		} catch(PDOException $e) {
			echo "Failed to execute query: ".$e->getMessage();
		}

	}
}

?>