<?php
// This is the main controller for the authorization
// form.

// File requires Twig and Admin class for propper work.
require_once 'vendor/autoload.php';
require_once "model/Admin.php";

// Extract the POST data.
$login = $_POST['login'];
$password = $_POST['password'];

// Instantiate Admin with the extracted data.
$admin = new Admin();
$admin->set_password($password);
$admin->set_login($login);

// If there was no error, user is logged in.
$error = $admin->login();

if( !$error ) {

	header('Location: index.php');

// If there was an error, render the authorization form
// with a modal window with an error record in it.
} else {

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	$template = $twig->load('auth.html');

	echo $template->render(['error' => $error[0]]);

}

?>