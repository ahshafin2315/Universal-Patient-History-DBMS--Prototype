<?php
session_start();
if (!isset($_SESSION['nid'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            /* Set a high z-index to ensure the header is on top of other content */
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
            /* Add your desired text color */
        }

        .logout-button a:hover {
            color: #007bff;
            /* Add your desired hover color */
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
        <?php
        require_once ('dbconnect.php');
        $nid = $_SESSION['nid'];
        // Fetch user data
        $sql = "SELECT * FROM User WHERE National_ID = $nid";
        $userdataArray = mysqli_fetch_array($conn->query($sql));
        ?>
        <h2>Dashboard</h2>

        <h3>Patient Details</h3>
        <table>
            <tr>
                <th>National ID</th>
                <td> <?php echo $nid ?> </td>
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

        <?php
        $sql = "SELECT r.Report_ID, r.Doctor_NID, r.Report_Description, r.Report_date
        FROM report r
        WHERE r.Patient_NID = $nid";
        $result = $conn->query($sql); ?>

        <h3>Past Consulted Doctors and Reports</h3>
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>Report ID</th>
                    <th>Doctor NID</th>
                    <th>Report Description</th>
                    <th>Report Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['Report_ID']; ?></td>
                        <td><?php echo $row['Doctor_NID']; ?></td>
                        <td><?php echo $row['Report_Description']; ?></td>
                        <td><?php echo $row['Report_date']; ?></td>
                        <td><a href="view_report.php?report_id=<?php echo $row['Report_ID']; ?>">View Details</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else {
            echo "No past consultations found.";
        }
        
        if ($userdataArray['dflag'] == 1) {
            $sql = "SELECT d.Specialization, d.Registration_ID, h.Hospital_Name, d.Consultation_Hours, d.Consultation_Days, d.Room_No, h.Address
            from doctor d, hospital_staff hs, Hospital h
            where d.National_ID=hs.National_ID 
            and hs.Hospital_ID=h.Hospital_ID 
            and d.National_ID = $nid";
            $doctorDetails = mysqli_fetch_array($conn->query($sql)); ?>
            <h3>Employee Details (Doctor)</h3>
            <table>
                <tr>
                    <th>Specialization</th>
                    <td> <?php echo $doctorDetails[0] ?> </td>
                </tr>
                <tr>
                    <th>Registration ID</th>
                    <td> <?php echo $doctorDetails[1] ?></td>
                </tr>
                <tr>
                    <th>Hospital</th>
                    <td> <?php echo $doctorDetails[2] ?></td>
                </tr>
                <tr>
                    <th>Consultation Hours</th>
                    <td> <?php echo $doctorDetails[3] ?></td>
                </tr>
                <tr>
                    <th>Consultation Days</th>
                    <td> <?php echo $doctorDetails[4] ?></td>
                </tr>
                <tr>
                    <th>Room No.</th>
                    <td> <?php echo $doctorDetails[5] ?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td> <?php echo $doctorDetails[6] ?></td>
                </tr>
            </table>
            <?php
            $sql = "SELECT c.Patient_NID, c.Consultation_Date, c.Consultation_Time
            FROM doctor d, consultations c
            WHERE d.National_ID=c.Doctor_NID
            AND d.National_ID = $nid";
            $result = $conn->query($sql); ?>
            <h3>Past Patient Visits</h3>
            <?php if ($result->num_rows > 0) { ?>
                <table>
                    <tr>
                        <th>Patient NID</th>
                        <th>Consultation_Date</th>
                        <th>Consultation_Time</th>
                    </tr> <?php
                    while ($pastPatients = mysqli_fetch_array($result)) { ?>
                        <tr>
                            <td><?php echo $pastPatients['Patient_NID']; ?></td>
                            <td><?php echo $pastPatients['Consultation_Date']; ?></td>
                            <td><?php echo $pastPatients['Consultation_Time']; ?></td>
                            <td><a href="view_patient_details.php?patient_nid=<?php echo $pastPatients['Patient_NID']; ?>">View
                                    Details</a></td>
                        </tr> <?php
                    } ?>
                </table> <?php
            } else {
                echo "No past patient visits found.";
            }
        } else if ($userdataArray["tflag"] == 1) {
            $sql = "SELECT t.Lab_Specialization, h.Hospital_Name, h.Address
            from Lab_Technician t, hospital_staff hs, Hospital h
            where t.National_ID=hs.National_ID
            and hs.Hospital_ID=h.Hospital_ID
            and t.National_ID = $nid";
            $technDetails = mysqli_fetch_array($conn->query($sql)); ?>
                <h3>Employee Details (Lab Technician)</h3>
                <table>
                    <tr>
                        <th>Lab Specialization</th>
                        <td> <?php echo $technDetails[0] ?> </td>
                    </tr>
                    <tr>
                        <th>Hospital</th>
                        <td> <?php echo $technDetails[1] ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td> <?php echo $technDetails[2] ?></td>
                    </tr>
                </table>
                <?php
                $sql = "SELECT l.*
            from lab_technician t, lab_tests l
            where t.National_ID=l.Technician_NID
            and t.National_ID = $nid";
                $result = $conn->query($sql); ?>
                <h3>Past Lab Tests</h3>
            <?php if ($result->num_rows > 0) { ?>
                    <table>
                        <tr>
                            <th>Patient NID</th>
                            <th>Doctor NID</th>
                            <th>Test_Type</th>
                            <th>Test_Date</th>
                        </tr> <?php
                        while ($pastTestsConducted = mysqli_fetch_array($result)) { ?>
                            <tr>
                                <td><?php echo $pastTestsConducted['Patient_NID']; ?></td>
                                <td><?php echo $pastTestsConducted['Doctor_NID']; ?></td>
                                <td><?php echo $pastTestsConducted['Test_Type']; ?></td>
                                <td><?php echo $pastTestsConducted['Test_Date']; ?></td>
                                <td><a href="view_lab_test.php?test_id=<?php echo $pastTestsConducted['Test_ID']; ?>">View
                                    Details</a></td>
                            </tr> <?php
                        } ?>
                    </table> <?php
            } else {
                echo "No past lab tests found.";
            }
        }

        $sql = "SELECT p.Illness, p.Current_Symptoms, m.Prescription_Date, m.Pharmaceuticals, m.Medicine_Name
        from patient p, med_prescribed m
        where p.National_ID=m.Patient_NID
        and p.National_ID = $nid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $patientDetails = $result->fetch_assoc(); ?>

            <h3>Current Illness</h3>
            <p><?php echo $patientDetails['Illness'] ?></p>
            <h3>Current Symptoms</h3>
            <p><?php echo $patientDetails['Current_Symptoms'] ?></p>
            <h3>Current Medication</h3>
            <table>
                <tr>
                    <th>Prescription_Date</th>
                    <td> <?php echo $patientDetails['Prescription_Date'] ?></td>
                </tr>
                <tr>
                    <th>Pharmaceuticals</th>
                    <td> <?php echo $patientDetails['Pharmaceuticals'] ?></td>
                </tr>
                <tr>
                    <th>Medicine_Name</th>
                    <td> <?php echo $patientDetails['Medicine_Name'] ?></td>
                </tr>
            </table> <?php
        } else { ?>
            <h3>Current Illness</h3>
            <p><?php echo "No current Illness!" ?></p>
            <h3>Current Symptoms</h3>
            <p><?php echo "No current symptoms!" ?></p>
            <h3>Current Medication</h3>
            <td> <?php echo "No current medications!" ?></td>
        <?php } ?>
    </div>

</body>

</html>