<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

if ($_SESSION['dflag'] != 1) {
	echo "Access denied. Only doctors can add reports.";
	exit;
}

$patient_nid = $_GET['patient_nid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$details = mysqli_real_escape_string($conn, $_POST['details']);
	$report_type = mysqli_real_escape_string($conn, $_POST['report_type']);
	$report_date = mysqli_real_escape_string($conn, $_POST['']);

	// Insert the report into the database
	$sql = "INSERT INTO report (Patient_NID, Doctor_NID, Report_Description, Report_Details, Report_Type, Report_date) 
	VALUES ('$patient_nid', '{$_SESSION['nid']}', '$description', '$details', '$report_type', NOW())";

	if ($conn->query($sql) === TRUE) {

		echo "Report added successfully";
	} else {
		echo "Error adding report: " . $conn->error;
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Report</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f5f5f5;
		}

		.container {
			max-width: 800px;
			margin: 50px auto;
			padding: 20px;
			background-color: #fff;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		#header {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 1.5cm;
			background-color: #ffffff;
			z-index: 1000;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		.row {
			max-width: 1300px;
			margin: 0 auto;
			/* Center the content horizontally */
		}

		.col-md-2 {
			text-align: right;
			padding-right: 20px;
			padding-top: 20px;
		}

		.logout-button a {
			font-size: 20px;
			text-decoration: none;
			color: #333333;
		}

		.logout-button a:hover {
			color: #007bff;
		}

		.s-button {
			text-align: center;
			margin-top: 20px;
		}

		.s-button a {
			font-size: 18px;
			text-decoration: none;
			color: #007bff;
		}

		.s-button a:hover {
			color: #0056b3;
		}

		h2 {
			text-align: center;
			color: #333;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		th,
		td {
			padding: 10px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}

		th {
			background-color: #f2f2f2;
		}

		select {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border: 1px solid #ccc;
			border-radius: 5px;
			box-sizing: border-box;
			font-size: 14px;
		}

		input[type="submit"] {
			width: 100%;
			padding: 10px;
			background-color: #007bff;
			color: #fff;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}

		input[type="submit"]:hover {
			background-color: #0056b3;
		}

		textarea {
			width: 100%;
			padding: 10px;
			margin-bottom: 20px;
			border: 1px solid #ccc;
			border-radius: 5px;
			box-sizing: border-box;
			resize: vertical;
			font-family: Arial, sans-serif;
			font-size: 14px;
		}

		textarea:focus {
			border-color: #007bff;
			outline: none;
		}
	</style>
</head>

<body>
	<section id="header">
		<div class="row">
			<div class="col-md-2">
				<?php if (isset($_SESSION['nid'])) { ?>
					<div class="logout-button">
						<a href="logout.php">Log Out</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<div class="container">
		<h2>Add Report</h2>
		<form method="post">
			<label for="description">Description:</label><br>
			<textarea id="description" name="description" rows="4" cols="50"></textarea><br>

			<label for="details">Details:</label><br>
			<textarea id="details" name="details" rows="7" cols="50"></textarea><br>

			<label for="report_type">Report Type:</label>
			<select id="report_type" name="report_type">
				<option value="General">General</option>
				<option value="Specialized">Specialized</option>
				<option value="Diagnostic">Diagnostic</option>
			</select><br>

			<input type="submit" value="Add Report">

			<div class="s-button">
				<a href="view_patient_details.php?patient_nid=<?php echo $patient_nid ?>">Back to Patient Details</a>
			</div>
		</form>
	</div>
</body>

</html>