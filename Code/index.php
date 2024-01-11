<?php
include('conn.php');
session_start();

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search_submit'])) {
    $searchByHash = isset($_GET['search_by_hash']) ? $_GET['search_by_hash'] : '';
    $searchByName = isset($_GET['search_by_name']) ? $_GET['search_by_name'] : '';
    $certificateDetails = "";

    if (!empty($searchByHash)) {
        // Search by certificate hash
        $sql = "SELECT * FROM certificates WHERE certificate_hash = '$searchByHash'";
    } elseif (!empty($searchByName)) {
        // Search by name
        $sql = "SELECT * FROM certificates WHERE name LIKE '%$searchByName%'";
    } else {
        // No search parameters provided
        echo "Please provide a search parameter.";
        exit;
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Certificates found
        $certificateDetails .= "<h2>Verification Results</h2>";
        while ($row = $result->fetch_assoc()) {
            $certificateDetails .= "Certificate Details:<br>";
            $certificateDetails .= "Name: " . $row['name'] . "<br>";
            $certificateDetails .= "Course Name: " . $row['coursename'] . "<br>";
            $certificateDetails .= "Date of Issue: " . $row['date_of_issue'] . "<br>";
            $certificateDetails .= "Certificate Hash: " . $row['certificate_hash'] . "<br><br>";
            // Add a download button for each certificate using JavaScript
            $certificateDetails .= '<a href="viewcert.php?certificate_hash=' . $row['certificate_hash'] . '">View Certificate</a><br><br>';
      
        }
    } else {
        // No certificates found
        $certificateDetails = "No certificates found.";
    }
}
?>
<?php include('nav.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Certificates</title><link rel="stylesheet" href="styles.css">

</head>
<body>

<h2>Search Certificates</h2>
<form action="index.php" method="get">
    <label for="search_by_hash">Search by Certificate Hash:</label>
    <input type="text" name="search_by_hash" required><br>

    <input type="hidden" name="search_submit" value="1"> <!-- Hidden input to indicate form submission -->

    <input type="submit" value="Search">
</form>

<form action="index.php" method="get">
    <label for="search_by_name">Search by Name:</label>
    <input type="text" name="search_by_name" required><br>

    <input type="hidden" name="search_submit" value="1"> <!-- Hidden input to indicate form submission -->

    <input type="submit" value="Search">
</form>
<?php echo $certificateDetails; ?>


</body>
</html>
