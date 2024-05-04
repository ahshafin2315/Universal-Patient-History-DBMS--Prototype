<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

if (isset($_GET['patient_nid'])) {

	$nid = $_SESSION['nid'];

	$patient_nid = mysqli_real_escape_string($conn, $_GET['patient_nid']);
	$sql = "SELECT * FROM User WHERE National_ID = '$patient_nid'";
	echo $sql; // Debugging statement
	$userdataArray = mysqli_fetch_array($conn->query($sql));

	$sql = "SELECT * FROM report WHERE Patient_NID = $patient_nid";
	$reportResult = $conn->query($sql);

	$sql = "SELECT * FROM med_prescribed WHERE Patient_NID = $patient_nid";
	$prescriptionResult = $conn->query($sql);

	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Patient Details</title>
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
			<h3>Patient Details</h3>
			<table>
				<tr>
					<th>National ID</th>
					<td> <?php echo $patient_nid ?> </td>
				</tr>
				<tr>
					<th>Name</th>
					<td> <?php echo $userdataArray['User_name'] ?></td>
				</tr>
				<tr>
					<th>Age</th>
					<td> <?php echo $userdataArray['Age'] ?></td>
				</tr>
				<tr>
					<th>Gender</th>
					<td> <?php echo $userdataArray['Gender'] ?></td>
				</tr>
				<tr>
					<th>Address</th>
					<td> <?php echo $userdataArray['addrss'] ?></td>
				</tr>
				<tr>
					<th>Email</th>
					<td> <?php echo $userdataArray['Email'] ?></td>
				</tr>
				<tr>
					<th>Blood Group</th>
					<td> <?php echo $userdataArray['Blood_Group'] ?></td>
				</tr>
				<tr>
					<th>Contact Info</th>
					<td> <?php echo $userdataArray['Contact_Info'] ?></td>
				</tr>
			</table>
			<h3>Previous Reports</h3>
			<?php if ($reportResult->num_rows > 0) { ?>
				<table>
					<thead>
						<tr>
							<th>Report ID</th>
							<th>Report Type</th>
							<th>Report Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($report = $reportResult->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $report['Report_ID']; ?></td>
								<td><?php echo $report['Report_Type']; ?></td>
								<td><?php echo $report['Report_date']; ?></td>
								<td><a href="view_report.php?report_id=<?php echo $report['Report_ID']; ?>">View Report</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo "No reports found.";
			} ?>
			<h3>Previous Prescriptions</h3>
			<?php if ($prescriptionResult->num_rows > 0) { ?>
				<table>
					<thead>
						<tr>
							<th>Prescription ID</th>
							<th>Pharmaceuticals</th>
							<th>Medicine Name</th>
							<th>Prescription_Date</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($presc = $prescriptionResult->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $presc['Prescription_ID']; ?></td>
								<td><?php echo $presc['Pharmaceuticals']; ?></td>
								<td><?php echo $presc['Medicine_Name']; ?></td>
								<td><?php echo $presc['Prescription_Date']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo "No prescriptions found.";
			} ?>
			<div class="s-button">
				<a href="add_report.php?patient_nid=<?php echo $patient_nid ?>">Add new Report</a>
			</div>
			<div class="s-button">
				<a href="user_dashboard.php">Back to Dashboard</a>
			</div>
		</div>
	</body>

	</html>
	<?php
} else {
	// parameter is not set in the URL
	echo "Patient_NID not specified.";
}
?>