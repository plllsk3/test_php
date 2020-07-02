<?php
// This is the main controller for the student add form.

// File requires Student, Student_list classes and
// Twig for propper work.
require_once "vendor/autoload.php";
require_once "model/Student.php";
require_once "model/Student_list.php";
require_once "model/Admin.php";

$admin = new Admin();

if ($admin->is_logged_in()) {

	// Check if there was a request for adding a student to db.
	if(isset($_POST)) {

		// Extract data from POST method.
		$lastname = htmlspecialchars( trim($_POST['lastname']) );
		$firstname = htmlspecialchars( trim($_POST['firstname']) );
		$midname = htmlspecialchars( ($_POST['midname']) );
		$bday = (int)$_POST['bday'];
		$bmonth = (int)$_POST['bmonth'];
		$byear = (int)$_POST['byear'];
		$class_id = (int)$_POST['class_id'];

		// Instantiate a Student and set extracted data.
		$student = new Student();

		$student->set_name($lastname, $firstname, $midname);
		$student->set_birthdate($bday, $bmonth, $byear);
		$student->set_class_id($class_id);

		// Check if the student isn't in db already.
		$student->check_record();

		// If there was no errors, record a student to db
		// and get back to the main page.
		if( $student->check_errors() === false ) {

			$student->record_to_db();

			header('Location: index.php');

		// If there was any error, render the main page with
		// a modal window and an error record in it.
		} else {
			$loader = new \Twig\Loader\FilesystemLoader('templates');
			$twig = new \Twig\Environment($loader);

			$template = $twig->load('index.html');

			$student_list = new Student_list();
			$student_list->set_classes();
			$classes = $student_list->get_classes();

			$errors = $student->check_errors();

			echo $template->render(['classes' => $classes, 'error' => $errors[0]]);
	}

	// If there wasn't a request, return to the main page.
	} else {
		header('Location: index.php');
	}

} else {
	header('Location: index.php');
}



?>