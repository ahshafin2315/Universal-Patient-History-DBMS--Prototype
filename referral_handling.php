<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$doctor_id = $_POST['doctor_id'];
	$report_id = $_POST['report_id'];

	$sql = "SELECT hs.Hospital_ID
		from doctor d, hospital_staff hs
		where d.National_ID=hs.National_ID
		and d.National_ID=$doctor_id";
	$result = $conn->query($sql);
	$hospital_id = $result->fetch_assoc()["Hospital_ID"];

	$sql = "INSERT INTO referrals(Report_ID, Referred_Doctor_ID, Referred_Hospital) VALUES ('$report_id','$doctor_id','$hospital_id')";
	if ($conn->query($sql) === TRUE) {
		echo "Referral added successfully";
		header("Location: view_report.php?report_id=$report_id");
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

if (isset($_GET["handling"]) && $_GET["handling"] == "delete") {

	$doctor_id = $_GET['doctor_id'];
	$report_id = $_GET['report_id'];

	$sql = "DELETE FROM referrals WHERE Report_ID = '$report_id' AND Referred_Doctor_ID = '$doctor_id'";
	if ($conn->query($sql) === TRUE) {
		echo "Referral removed successfully";
		header("Location: view_report.php?report_id=$report_id");
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

}