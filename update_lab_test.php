<?php
session_start();
if (!isset($_SESSION['nid'])) {
    header("Location: login.php");
    exit;
}
require_once('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_id = mysqli_real_escape_string($conn, $_POST['test_id']);
    $results = mysqli_real_escape_string($conn, $_POST['results']);
    $Test_Type = mysqli_real_escape_string($conn, $_POST['Test_Type']);
    
    // Update the report in the database
    $sql = "UPDATE lab_tests SET Test_Results='$results', Test_Type='$Test_Type' WHERE Test_ID='$test_id'";
    
    if ($conn->query($sql) === TRUE) {
		header("Location: view_lab_test.php?test_id=$test_id&success=true");
		exit();
	} else {
		header("Location: view_lab_test.php?test_id=$test_id&error=true");
		exit();
	}
}
?>
