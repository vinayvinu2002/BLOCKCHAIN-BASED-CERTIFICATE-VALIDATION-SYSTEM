<?php
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $enteredOTP = $_POST['otp'];

    // Validate input (you might want to add more validation)
    if (empty($enteredOTP)) {
        echo "Please enter the OTP.";
        exit;
    }

    // Retrieve the stored OTP for the user
    $getOTPQuery = "SELECT otp FROM users WHERE username = '$username'";
    $result = $conn->query($getOTPQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedOTP = $row['otp'];

        // Check if entered OTP matches the stored OTP
        if ($enteredOTP == $storedOTP) {
            // Mark the email as verified (you might want to add this field to your database)
            $updateVerifiedQuery = "UPDATE users SET email_verified = 1 WHERE username = '$username'";
            $conn->query($updateVerifiedQuery);

            echo "Email verified successfully!";
        } else {
            echo "Incorrect OTP. Please try again.";
        }
    } else {
        echo "Error retrieving OTP.";
    }
}
?>
