<?php
session_start();
if (!isset($_SESSION['nid'])) {
	header("Location: login.php");
	exit;
}
require_once ('dbconnect.php');


if (isset($_GET['report_id'])) {
	$report_id = mysqli_real_escape_string($conn, $_GET['report_id']);
	
	$sql = "SELECT d.National_ID, u.User_name, d.Specialization, h.Hospital_Name, hs.Hospital_ID
	from doctor d, user u, hospital_staff hs, Hospital h
	where d.National_ID=u.National_ID 
	and d.National_ID=hs.National_ID
	and hs.Hospital_ID=h.Hospital_ID;";
	$result = $conn->query($sql);
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
			<h2>Select Doctor</h2>
			<form action="referral_handling.php" method="POST">
				<label for="doctor_id">Select a Doctor:</label><br>
				<select id="doctor_id" name="doctor_id" required>
					<?php
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							echo '<option value="' . $row['National_ID'] . '">' . $row['User_name'] . ' - ' . $row['Specialization']. ' - ' .$row['Hospital_Name'] . '</option>';
						}
					} else {
						echo '<option value="">No doctors available</option>';
					}
					?>
				</select><br>
				<input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
				<input type="submit" value="Submit">
			</form>
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