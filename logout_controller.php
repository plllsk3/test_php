<?php
// This is the main controller for the logout request.

// File requires Admin class for propper work.
require_once "model/Admin.php";

// Checks if there was a request for logging out.
if ($_GET['action']) {

	// Log out.
	$admin = new Admin();
	$admin->logout();

	header('Location: index.php');

// If there wasn't a request, return to the main page.
} else {
	header('Location: index.php');
}

?>