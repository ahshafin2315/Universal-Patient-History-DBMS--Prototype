<?php
session_start();
require_once ('dbconnect.php');

if (!isset($_SESSION['nid']) && $_SESSION['adflag'] != 1) {
	header("Location: login.php");
	exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$test_id = $_POST['test_id'];

	$sql = "DELETE FROM lab_tests WHERE Test_ID = $test_id";
	
	if ($conn->query($sql) === TRUE) {
		header("Location: adminpage.php?success=true");
		exit();
	} else {
		header("Location: adminpage.php?error=true");
		exit();
	}
}

$hospital_id = isset($_GET['hospital_id']) ? $_GET['hospital_id'] : null;
if (!$hospital_id) {
	header("Location: adminpage.php?error=Hospital ID not provided");
	exit();
}

$sql = "SELECT Hospital_Name FROM Hospital WHERE Hospital_ID = '$hospital_id'";
$result = $conn->query($sql);
$hospital_name = $result->fetch_assoc()['Hospital_Name'];

$sql = "SELECT lt.*, hs.Hospital_ID, u.User_name
        FROM lab_tests lt, User u, hospital_staff hs
        WHERE lt.Technician_NID = hs.National_ID
		AND lt.Technician_NID = u.National_ID
        AND hs.Hospital_ID = $hospital_id
        ORDER BY lt.Test_Date DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Delete Lab Test Issue</title>
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
		<h2>Delete Lab Test Issue for <?php echo $hospital_name; ?></h2>

		<form method="post">
			<label for="test_id">Select Lab Test:</label>
			<select name="test_id" id="test_id">
				<?php while ($row = $result->fetch_assoc()) { ?>
					<option value="<?php echo $row['Test_ID']; ?>">
						<?php echo $row['User_name'] . ' - ' . $row['Test_Date']; ?>
					</option>
				<?php } ?>
			</select>
			<br>
			<input type="submit" value="Delete Lab Test Issue">
		</form>
		<div class="s-button">
			<a href="adminpage.php">Back to Admin Page</a>
		</div>
	</div>
</body>

</html>