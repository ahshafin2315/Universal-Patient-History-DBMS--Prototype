<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

if (isset($_GET['test_id'])) {

	$test_id = mysqli_real_escape_string($conn, $_GET['test_id']);

	$sql = "SELECT * FROM lab_tests WHERE Test_ID = $test_id";
	$lab_testQ = $conn->query($sql);
	$lab_test = $lab_testQ->fetch_assoc();
	$patient_nid = $lab_test['Patient_NID'];

	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>View lab_test</title>
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

			<h2>Lab Test Details</h2>

			<p><strong>Test ID:</strong> <?php echo $lab_test['Test_ID']; ?></p>
			<p><strong>Patient NID:</strong> <?php echo $lab_test['Patient_NID']; ?></p>
			<p><strong>Doctor NID:</strong> <?php echo $lab_test['Doctor_NID']; ?></p>
			<p><strong>Result Description:</strong> <?php echo $lab_test['Test_Results']; ?></p>
			<p><strong>Test Type:</strong> <?php echo $lab_test['Test_Type']; ?></p>
			<p><strong>Test Date:</strong> <?php echo $lab_test['Test_Date']; ?></p>

			<?php if ($_SESSION['tflag'] == 1 && $_SESSION['nid'] == $lab_test['Technician_NID']) { ?>
				<div class="s-button">
					<a href="edit_lab_test.php?test_id=<?php echo $lab_test['Test_ID']; ?>">Edit Test</a>
				</div>
			<?php } ?>
			<div class="s-button">
				<a href="user_dashboard.php">Back to Dashboard</a>
			</div>

		</div>
	</body>

	</html>
	<?php

} else {
	// test_id parameter is not set in the URL
	echo "Test ID not specified.";
}
?>