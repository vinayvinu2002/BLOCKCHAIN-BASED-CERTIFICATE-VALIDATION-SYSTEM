<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csvFile = $_FILES['csv_file'];

    if ($csvFile['error'] == UPLOAD_ERR_OK) {
        $csvFileName = $csvFile['name'];
        $tmpName = $csvFile['tmp_name'];

        // Check if the uploaded file is a CSV
        $fileInfo = pathinfo($csvFileName);
        $extension = strtolower($fileInfo['extension']);

        if ($extension === 'csv') {
            // Process the CSV file
            $handle = fopen($tmpName, 'r');

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Extract data from the CSV row
                $name = $data[0];
                $coursename = $data[1];
                $date_of_issue = $data[2];
                $organization_name = $data[3];

                // Create a certificate in the database
                $certificateHash = sha1($name . $coursename . $date_of_issue . $organization_name . microtime());

                $insertSql = "INSERT INTO certificates (user_id, name, coursename, date_of_issue, organization_name, certificate_hash) 
                              VALUES ('$user_id', '$name', '$coursename', '$date_of_issue', '$organization_name', '$certificateHash')";

                if ($conn->query($insertSql) === TRUE) {
                    echo "Certificates created successfully from the CSV file.";
                } else {
                    echo "Error inserting data into the database: " . $conn->error;
                }
            }

            fclose($handle);

            echo "Certificates created successfully from the CSV file.";
        } else {
            echo "Please upload a valid CSV file.";
        }
    } else {
        echo "Error uploading the file.";
    }
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bulk Certificates</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php include('nav.php'); ?>

    <div class="container">
        <h2>Create Bulk Certificates</h2>

        <form action="generate_bulk.php" method="post" enctype="multipart/form-data">
            <label for="csv_file">Upload CSV File:</label>
            <input type="file" name="csv_file" required accept=".csv"><br>

            <input type="submit" value="Create Certificates">
        </form>
    </div>

</body>

</html>