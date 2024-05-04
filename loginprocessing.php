<?php
session_start();
require_once ('dbconnect.php');

if (isset($_POST['nid']) && isset($_POST['password'])) {
    $nid = $_POST['nid'];
    $passwd = $_POST['password'];

    $sql = "SELECT * FROM User WHERE National_ID='$nid' AND passwd='$passwd'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $userData = $result->fetch_assoc();
        $_SESSION["nid"] = $nid;
        $_SESSION["dflag"] = $userData["dflag"];
        $_SESSION["tflag"] = $userData["tflag"];
        $_SESSION["pflag"] = $userData["pflag"];
        $_SESSION["adflag"] = $userData["adflag"];

        if ($userData['adflag'] == 1) {
            header("Location: adminpage.php");
            exit();
        } else {
            header("Location: user_dashboard.php");
            exit();
        }
    } else {

        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
