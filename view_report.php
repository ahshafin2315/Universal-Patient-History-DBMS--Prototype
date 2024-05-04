<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

if (isset($_GET['report_id'])) {

	$report_id = mysqli_real_escape_string($conn, $_GET['report_id']);

	$sql = "SELECT * FROM report WHERE Report_ID = $report_id";
	$reportQ = $conn->query($sql);
	$report = $reportQ->fetch_assoc();
	$patient_nid = $report['Patient_NID'];

	$sql = "SELECT * FROM med_prescribed WHERE Patient_NID = $patient_nid and Report_ID = $report_id";
	$prescriptionQ = $conn->query($sql);

	$sql = "SELECT * FROM referrals WHERE Report_ID = $report_id";
	$refQ = $conn->query($sql);

	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>View Report</title>
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

			<h2>Report Details</h2>

			<p><strong>Report ID:</strong> <?php echo $report['Report_ID']; ?></p>
			<p><strong>Patient NID:</strong> <?php echo $report['Patient_NID']; ?></p>
			<p><strong>Doctor NID:</strong> <?php echo $report['Doctor_NID']; ?></p>
			<p><strong>Report Description:</strong> <?php echo $report['Report_Description']; ?></p>
			<p><strong>Report Type:</strong> <?php echo $report['Report_Type']; ?></p>
			<p><strong>Report Details:</strong> <?php echo $report['Report_Details']; ?></p>
			<p><strong>Report Date:</strong> <?php echo $report['Report_date']; ?></p>

			<h2>Prescription_Details</h2>

			<?php if ($prescriptionQ && $prescriptionQ->num_rows > 0) { ?>
				<table>
					<thead>
						<tr>
							<th>Prescription ID</th>
							<th>Prescription Date</th>
							<th>Pharmaceuticals</th>
							<th>Medicine Name</th>
							<th>Usage</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($prescription = $prescriptionQ->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $prescription['Prescription_ID']; ?></td>
								<td><?php echo $prescription['Prescription_Date']; ?></td>
								<td><?php echo $prescription['Pharmaceuticals']; ?></td>
								<td><?php echo $prescription['Medicine_Name']; ?></td>
								<td><?php echo $prescription['use_state'] == 0 ? 'Discontinued' : 'Continued'; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo 'No prescriptions given!';
			} ?>

			<h2>Referrals</h2>

			<?php if ($refQ && $refQ->num_rows > 0) {
				?>
				<table>
					<thead>
						<tr>
							<th>Referred To</th>
							<th>Name</th>
							<th>Specialization</th>
							<th>Hospital</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($ref = $refQ->fetch_assoc()) {
							$doctor_id = $ref['Referred_Doctor_ID'];
							$hospital_id = $ref['Referred_Hospital'];
							$sql1 = "SELECT u.User_name, d.Specialization
								from doctor d, user u
								where d.National_ID=u.National_ID
								and d.National_ID=$doctor_id";
							$result1Q = $conn->query($sql1);
							$result1 = $result1Q->fetch_assoc();
							$sql2 = "SELECT Hospital_Name
								from Hospital
								where Hospital_ID=$hospital_id";
							$result2Q = $conn->query($sql2);
							$result2 = $result2Q->fetch_assoc(); ?>
							<tr>
								<td><?php echo $doctor_id; ?></td>
								<td><?php
								echo $result1["User_name"];
								?></td>
								<td><?php
								echo $result1["Specialization"];
								?></td>
								<td><?php echo $result2["Hospital_Name"]; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo 'No Referrals!';
			} ?>


			<?php if ($_SESSION['dflag'] == 1 && $_SESSION['nid'] == $report['Doctor_NID']) { ?>
				<div class="s-button">
					<a href="edit_report.php?report_id=<?php echo $report_id; ?>">Edit Report</a>
				</div>
				<div class="s-button">
					<a href="add_medicine.php?patient_nid=<?php echo $patient_nid ?>&report_id=<?php echo $report_id; ?>">Add a
						Prescription</a>
				</div>
				<div class="s-button">
					<a href="view_patient_details.php?patient_nid=<?php echo $patient_nid; ?>">Back to Patient Details</a>
				</div>

				<div class="s-button">
					<a href="add_referral.php?report_id=<?php echo $report_id; ?>">Add Referral</a>
				</div>

				<div class="s-button">
					<a href="delete_referral.php?report_id=<?php echo $report_id; ?>">Remove Referral</a>
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
	// report_id parameter is not set in the URL
	echo "Report ID not specified.";
}
?>