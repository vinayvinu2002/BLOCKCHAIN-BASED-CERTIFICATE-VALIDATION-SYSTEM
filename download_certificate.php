<?php
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['certificate_hash'])) {
    $certificateHash = $_GET['certificate_hash'];

    // Generate certificate content based on the certificate hash
    // Replace the following line with your actual code to generate the certificate content
    $certificateContent = generateCertificateContent($certificateHash);

    // Check if the certificate content is not empty
    if (!empty($certificateContent)) {
        // Set appropriate headers for file download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="certificate.pdf"');
        header('Content-Length: ' . strlen($certificateContent));

        // Output the certificate content
        echo $certificateContent;
        exit;
    } else {
        echo 'Certificate not found.';
    }
} else {
    echo 'Invalid request.';
}

function generateCertificateContent($certificateHash) {
    // Replace this function with your actual logic to generate the certificate content
    // Example: Use a PDF generation library to create a PDF certificate
    // For demonstration, let's assume a simple PDF content here
    $pdfContent = 'PDF content for certificate with hash ' . $certificateHash;
    return $pdfContent;
}
?>
