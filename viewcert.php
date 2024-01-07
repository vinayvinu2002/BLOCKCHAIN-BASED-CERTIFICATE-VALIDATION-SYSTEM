<?php
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['certificate_hash'])) {
    $certificateHash = $_GET['certificate_hash'];

    $certificateDetails = getCertificateDetails($certificateHash);

    if ($certificateDetails) {
        echo '<h2>Certificate of Completion</h2>';
        echo '<div class="certificate-content">';
        echo '<p class="recipient">This is to certify that</p>';
        echo '<p class="name">' . $certificateDetails['name'] . '</p>';
        echo '<p class="course">has successfully completed the course</p>';
        echo '<p class="course-name">' . $certificateDetails['coursename'] . '</p>';
        echo '<p class="date">on ' . $certificateDetails['date_of_issue'] . '</p>';
        echo '<p class="hash">Certificate Hash: ' . $certificateHash . '</p>'; // Include the certificate hash
        echo '</div>';

        // Provide a button to view and print the certificate
        echo '<button id="printCertificate">Print Certificate</button>';
      } else {
        echo 'Certificate not found.';
    }
} else {
    echo 'Invalid request.';
}

function getCertificateDetails($certificateHash) {
    global $conn;

    $sql = "SELECT name, coursename, date_of_issue FROM certificates WHERE certificate_hash = '$certificateHash'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>

<style>
 
    @media print {
        /* Styles specific for printing */
        body {
            font-family: "Times New Roman", Times, serif;
            text-align: center;
            background-color: #f5f5f5;
            color: #333;
        }

        .certificate-content {
            border: 5px solid #3498db;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #3498db;
        }

        .recipient, .course, .hash {
            font-size: 18px;
            margin: 10px 0;
        }

        .name, .course-name {
            font-size: 24px;
            font-weight: bold;
        }

        /* Add additional print styles as needed */
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('printCertificate').addEventListener('click', function () {
        window.print();
    });
});
</script>