<?php
// This is the main controller for the reports section.

// File requires Report class for propper work.
require_once 'model/Report.php';

// Extract a request data.
$code = $_GET['code'];
$opt_id = (int)$_GET['id'];

// Instantiate Report and load data from db.
$report = new Report($code, $opt_id);
$report->load_report();

$report_data = $report->get_report();

$amount = count($report_data);

// Make required output.
for($i = 0; $i < count($report_data); $i++) {
	$name = $report_data[$i]['name'];
	$class = $report_data[$i]['class'];
	$birthdate = $report_data[$i]['birthdate'];
	$id = $report_data[$i]['id'];

	echo "<tr data-stid='{$id}'><td>{$name}</td><td>{$class}</td><td>{$birthdate}</td></tr>";
}

echo "<tr><td colspan='3'>Всего: {$amount}</td></tr>";

?>