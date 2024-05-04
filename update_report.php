<?php
session_start();
if (!isset($_SESSION['nid'])) {
    header("Location: login.php");
    exit;
}
require_once('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = mysqli_real_escape_string($conn, $_POST['report_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $report_type = mysqli_real_escape_string($conn, $_POST['report_type']);
    
    // Update the report in the database
    $sql = "UPDATE report SET Report_Description='$description', Report_Details='$details', Report_Type='$report_type' WHERE Report_ID='$report_id'";
    
    if ($conn->query($sql) === TRUE) {
		header("Location: view_report.php?report_id=$report_id&success=true");
		exit();
	} else {
		header("Location: view_report.php?report_id=$report_id&error=true");
		exit();
	}
}
?>
