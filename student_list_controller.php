<?php
// This is the main controller of the student list section.
// It receives requests for students in a chosen class 
// and it also handles a student removal.

// File requires Student_list class for propper work.
require_once "model/Student_list.php";

// Check if the request was for a class display.
if( isset($_GET['id']) && !isset($_GET['del']) ) {
	$cl_id = (int)$_GET['id'];

	// Create Student_list instance and load
	// required student list.
	$student_list = new Student_list();

	$student_list->set_students($cl_id);

	$students = $student_list->get_students();

	// Make required output.
	for($i = 0; $i < count($students); $i++) {
		$name = $students[$i]['name'];
		$class = $students[$i]['class'];
		$birthdate = $students[$i]['birthdate'];
		$id = $students[$i]['id'];

		echo "<tr data-stid='{$id}'><td>{$name}</td><td>{$class}</td><td>{$birthdate}</td></tr>";
	} 

// Check if the request was for a student removal.
// If so, remove a chosen student.
} elseif (isset($_GET['id']) && isset($_GET['del'])) {
		$st_id = (int)$_GET['id'];

		$student_list = new Student_list();
		$student_list->delete_student($st_id);
}

?>