<?php
session_start();
require_once ('dbconnect.php');

if (!isset($_SESSION['nid']) && $_SESSION['adflag'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $patient_id = $_POST['patient_id'];
    $technician_id = $_POST['technician_id'];
    $doctor_id = $_POST['doctor_id'];
    $test_type = $_POST['test_type'];
    $test_date = $_POST['test_date'];

    // Perform database insertion
    $sql = "INSERT INTO lab_tests (Technician_NID, Patient_NID, Doctor_NID, Test_Type, Test_Date)
        VALUES ('$technician_id', '$patient_id', '$doctor_id', '$test_type', '$test_date')";

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
    header("Location: admin_panel.php?error=Hospital ID not provided");
    exit();
}

$sql = "SELECT Hospital_Name FROM Hospital WHERE Hospital_ID = '$hospital_id'";
$result = $conn->query($sql);
$hospital_name = $result->fetch_assoc()['Hospital_Name'];

$sql = "SELECT * FROM User where adflag = 0";
$patient_result = $conn->query($sql);

?>

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Lab Test</title>
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
        <h2>Issue Lab Test for <?php echo $hospital_name; ?></h2>
        <form method="post">
            <label for="patient_id">Select Patient:</label>
            <select name="patient_id" id="patient_id">
                <?php mysqli_data_seek($patient_result, 0); ?>
                <?php while ($row = $patient_result->fetch_assoc()) { ?>
                    <option value="<?php echo $row['National_ID']; ?>"><?php echo $row['User_name']; ?></option>
                <?php } ?>
            </select>
            <br>
            <label for="technician_id">Select Lab Technician:</label>
            <select name="technician_id" id="technician_id">
                <?php
                $sql = "SELECT u.User_name, t.*
                FROM user u, lab_technician t, hospital_staff hs
                WHERE u.National_ID=t.National_ID
                AND u.National_ID=hs.National_ID
                AND hs.hospital_ID=$hospital_id";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    // Concatenate doctor's name with consultation days and hours
                    $techn_info = $row['User_name'] . ' - ' . $row['Lab_Specialization'];
                    echo "<option value='" . $row['National_ID'] . "'>" . $techn_info . "</option>";
                } ?>
            </select>
            <label for="doctor_id">Select Doctor:</label>
            <select name="doctor_id" id="doctor_id">
                <?php
                $sql = "SELECT u.User_name, d.National_ID, d.Consultation_Hours, d.Consultation_Days
                FROM user u, doctor d, hospital_staff hs, Hospital h
                WHERE u.National_ID=d.National_ID
                AND d.National_ID=hs.National_ID
                AND hs.Hospital_ID=h.Hospital_ID
                AND hs.hospital_ID=$hospital_id";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    // Concatenate doctor's name with consultation days and hours
                    $doctor_info = $row['User_name'] . ' - Days: ' . $row['Consultation_Days'] . ', Hours: ' . $row['Consultation_Hours'];
                    echo "<option value='" . $row['National_ID'] . "'>" . $doctor_info . "</option>";
                }
                ?>
            </select>
            <br>
            <label for="test_type">Test Type:</label>
            <input type="text" name="test_type" id="test_type">
            <br>
            <label for="test_date">Test Date:</label>
            <input type="date" name="test_date" id="test_date">
            <br>
            <input type="submit" value="Issue Lab Test">
        </form>
        <div class="s-button">
            <a href="adminpage.php">Back to Admin Page</a>
        </div>
    </div>
</body>

</html>