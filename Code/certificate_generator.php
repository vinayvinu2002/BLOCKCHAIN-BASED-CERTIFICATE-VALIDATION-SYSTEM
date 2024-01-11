<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $coursename = $_POST['coursename'];

    $certificateHash = md5($name . $coursename . time());

    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO certificates (user_id, name, coursename, date_of_issue, certificate_hash)
            VALUES ('$userId', '$name', '$coursename', NOW(), '$certificateHash')";

    if ($conn->query($sql) === TRUE) {
        echo "Certificate generated successfully! Certificate Hash: $certificateHash";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$searchResult = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $certificateHash = $_GET['certificate_hash'];

    // Fetch certificate details based on the provided certificate hash
    $sql = "SELECT * FROM certificates WHERE certificate_hash = '$certificateHash'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $searchResult = "Certificate Found - Name: " . $row['name'] . ", Course Name: " . $row['coursename'] . ", Date of Issue: " . $row['date_of_issue'];
    } else {
        $searchResult = "Certificate not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Generator</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<?php include('nav.php'); ?>

<h2>Generate Certificate</h2>
<form action="certificate_generator.php" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" required><br>

    <label for="coursename">Course Name:</label>
    <input type="text" name="coursename" required><br>

    <input type="submit" value="Generate Certificate">
</form>

<h2>Search Certificate</h2>
<form action="certificate_generator.php" method="get">
    <label for="certificate_hash">Certificate Hash:</label>
    <input type="text" name="certificate_hash" required><br>

    <input type="submit" value="Search Certificate">
</form>
<p><?php echo $searchResult; ?></p>

<!-- <p><a href="logout.php">Logout</a></p> -->

</body>
</html>
