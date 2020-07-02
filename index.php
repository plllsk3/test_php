<?php
// This is the main controller of the app.

// File requires Admin, Student_list classes
// and Twig for propper work.
require_once 'vendor/autoload.php';
require_once 'model/Admin.php';
require_once 'model/Student_list.php';

// Load Twig and templates.
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$template_main = $twig->load('index.html');
$template_auth = $twig->load('auth.html');

$admin = new Admin();

// Check if user is already logged id.
if ( $admin->is_logged_in() ){

	$student_list = new Student_list();
	$student_list->set_classes();
	$classes = $student_list->get_classes();

	echo $template_main->render(['classes' => $classes]);

// If not, show the authorization form.
} else {

	echo $template_auth->render();
}

?>