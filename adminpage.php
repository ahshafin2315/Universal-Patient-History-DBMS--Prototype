<?php
session_start();
require_once ('dbconnect.php');

if (!isset($_SESSION['nid']) && $_SESSION['adflag'] != 1) {
	header("Location: login.php");
	exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_id = $_POST['hospital_id'];
	if ($_POST['action'] == 'add_consultation') {
		header("Location: add_consultation.php?hospital_id=$hospital_id");
		exit();
	} elseif ($_POST['action'] == 'delete_consultation') {
		header("Location: delete_consultation.php?hospital_id=$hospital_id");
		exit();
	} elseif ($_POST['action'] == 'issue_lab_test') {
		header("Location: issue_lab_test.php?hospital_id=$hospital_id");
		exit();
	} elseif ($_POST['action'] == 'delete_lab_test_issue') {
		header("Location: delete_lab_test_issue.php?hospital_id=$hospital_id");
		exit();
	}
}

$success_message = isset($_GET['success']) ? "Operation successful." : "";
$error_message = isset($_GET['error']) ? "An error occurred. Please try again." : "";

$sql = "SELECT * FROM Hospital";
$hospital_result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Panel</title>
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
		<h1>Welcome to the Admin Panel</h1>
		<p><?php echo $success_message; ?></p>
		<p><?php echo $error_message; ?></p>

		<h2>Please Select Data to Update</h2>
		<form method="post">
			<label for="hospital_id">Select Hospital:</label>
			<select name="hospital_id" id="hospital_id">
				<?php while ($row = $hospital_result->fetch_assoc()) { ?>
					<option value="<?php echo $row['Hospital_ID']; ?>"><?php echo $row['Hospital_Name']; ?></option>
				<?php } ?>
			</select>
			<br>
			<label for="action">Select Action:</label>
			<select name="action" id="action">
				<option value="add_consultation">Add Consultation</option>
				<option value="delete_consultation">Delete Consultation</option>
				<option value="issue_lab_test">Issue Lab Test</option>
				<option value="delete_lab_test_issue">Delete Lab Test Issue</option>
			</select>
			<br>
			<input type="submit" value="Go">
		</form>
	</div>
</body>

</html>