<?php
include('conn.php');

// Function to generate a random 6-digit OTP
function generateOTP() {
    return rand(100000, 999999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generateOTP'])) {
    $username = $_POST['username'];

    // Generate OTP
    $otp = generateOTP();

     
     // Send OTP via email
     $to = $username; // Replace with the recipient's email address
     $subject = 'Your OTP';
     $message = 'Your OTP is: ' . $otp;
 
     // Additional headers
     $headers = 'From: 979error@gmail.com' . "\r\n" .
                'Reply-To: 979error@gmail' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
 
     // Use the mail function to send the email
     if (mail($to, $subject, $message, $headers)) {
         echo 'OTP sent successfully.';
     } else {
         echo 'Error sending OTP.';
     }

    // Insert OTP into the new table
    $insertOTPSql = "INSERT INTO email_otp (email, otp) VALUES ('$username', '$otp')";
    if ($conn->query($insertOTPSql) === TRUE) {
        // Update OTP in the users table
        $updateSql = "UPDATE users SET otp = '$otp' WHERE username = '$username'";
        if ($conn->query($updateSql) === TRUE) {
            // Send OTP to the user's email (You need to implement this part)
            echo "OTP sent to your email. Please enter the OTP below.";

            // Redirect to register.php with username as a parameter
            ob_start();
            header("Location: register.php?username=$username");
            ob_end_flush();
            exit();
        } else {
            echo "Error updating OTP in the users table: " . $conn->error;
        }
    } else {
        echo "Error inserting OTP into the email_otp table: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['username'])) {
    $username = $_GET['username'];
    $enteredOTP = $_GET['otp'];

    // Retrieve the stored OTP from the new table
    $sql = "SELECT otp FROM email_otp WHERE email = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedOTP = $row['otp'];

        if ($enteredOTP == $storedOTP) {
            // OTP is verified, redirect to register.php
            ob_start();
            header("Location: register.php?username=$username");
            ob_end_flush();
            exit();
        } else {
            echo "Invalid OTP. Please try again.";
        }
    } else {
        echo "Error retrieving OTP from the new table.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include('nav.php'); ?>

    <h2>User Registration</h2>
    <form action="" method="post">
        <label for="username">E-mail:</label>
        <input type="email" name="username" required><br>
        <button type="submit" name="generateOTP">Get OTP</button>
    </form>
</body>
</html>
