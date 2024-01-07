<?php
session_start();
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $certificateHash = $_GET['certificate_hash'];

    $sql = "SELECT * FROM certificates WHERE certificate_hash = '$certificateHash'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "Certificate Details:<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Course Name: " . $row['coursename'] . "<br>";
        echo "Date of Issue: " . $row['date_of_issue'] . "<br>";
    } else {
        echo "Certificate not found.";
    }
}
?>
