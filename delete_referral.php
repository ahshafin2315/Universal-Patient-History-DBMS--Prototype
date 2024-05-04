<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');

// Retrieve the list of doctors from the database
$sql = "SELECT * FROM doctor";
$result = $conn->query($sql);

if (isset($_GET['report_id'])) {
	$report_id = mysqli_real_escape_string($conn, $_GET['report_id']);

	$sql = "SELECT * FROM referrals WHERE Report_ID = $report_id";
	$refQ = $conn->query($sql);

	?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Select Doctor</title>
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
		<div class="container">
			<h2>Delete Referrals</h2>

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
								<td><a href="referral_handling.php?report_id=<?php echo $report_id; ?>&doctor_id=<?php echo $doctor_id; ?>&handling=delete">Remove</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo 'No Referrals to delete!';
			} ?>
			<div class="s-button">
				<a href="view_report.php?report_id=<?php echo $_GET['report_id']; ?>">Back to Report Details</a>
			</div>
		</div>
	</body>

	</html>

	<?php

} else {
	echo "Report ID or Referred Doctor ID not specified.";
}
?>