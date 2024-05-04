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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$patient_nid = mysqli_real_escape_string($conn, $_POST['patient_nid']);
	$report_id = mysqli_real_escape_string($conn, $_POST['report_id']);
	$pharmaceuticals = mysqli_real_escape_string($conn, $_POST['pharmaceuticals']);
	$medicine = mysqli_real_escape_string($conn, $_POST['medicine']);

	$sql = "INSERT INTO med_prescribed (Prescription_Date, Patient_NID, Doctor_NID, Report_ID, Pharmaceuticals, Medicine_Name, use_state) 
	VALUES (NOW(), '$patient_nid', '{$_SESSION['nid']}', '$report_id', '$pharmaceuticals', '$medicine', 1)";

	if ($conn->query($sql) === TRUE) {
		echo "Medicine added successfully";
	} else {
		echo "Error adding medicine: " . $conn->error;
		header("add_medicine.php?report_id=$report_id");
	}
}

$patient_nid = $_GET['patient_nid'];
$report_id = $_GET['report_id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Medication</title>
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

	<div class="container">
		<h2>Add Medication</h2>
		<form action="add_medicine.php" method="post">
			<?php $sql = "SELECT * FROM medicine";
			$result = $conn->query($sql); ?>
			<label for="pharmaceuticals">Pharmaceuticals:</label><br>
			<select id="pharmaceuticals" name="pharmaceuticals">
				<?php while ($row = $result->fetch_assoc()) {
					echo '<option value="' . $row['Pharmaceuticals'] . '">' . $row['Pharmaceuticals'] . ' - ' . $row['Medicine_Name'] . '</option>';
				} ?>
			</select>
			<?php mysqli_data_seek($result,0); ?>
			<label for="medicine">Medicine:</label><br>
			<select id="medicine" name="medicine">
				<?php while ($row = $result->fetch_assoc()) {
					echo '<option value="' . $row['Medicine_Name'] . '">' . $row['Pharmaceuticals'] . ' - ' . $row['Medicine_Name'] . '</option>';
				} ?>
			</select>
			<input type="hidden" name="patient_nid" value="<?php echo $patient_nid; ?>">
			<input type="hidden" name="report_id" value="<?php echo $report_id; ?>">

			<input type="submit" value="Select Medicine">
		</form>
		<div class="s-button">
			<a href="view_report.php?report_id=<?php echo $report_id; ?>">Back to Report</a>
		</div>
		<div class="s-button">
			<a href="user_dashboard.php">Back to Dashboard</a>
		</div>
	</div>
</body>

</html>