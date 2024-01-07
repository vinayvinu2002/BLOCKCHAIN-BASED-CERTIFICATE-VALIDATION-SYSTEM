<?php
include('conn.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to generate a random 6-digit OTP
function generateOTP() {
    return rand(100000, 999999);
}

// Check if the page is accessed with GET and has a valid 'username' parameter
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Insert the user's email into the users table if it doesn't exist
    $checkUserSql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUserSql);

    if (!$result || $result->num_rows === 0) {
        // User does not exist, insert into users table
        // Assuming 'password' is the name of the column in the 'users' table
        // You need to replace 'DEFAULT_PASSWORD' with the default or hashed password
        $insertUserSql = "INSERT INTO users (username, password) VALUES ('$username', 'DEFAULT_PASSWORD')";
        if ($conn->query($insertUserSql) !== TRUE) {
            echo "Error inserting user: " . $conn->error;
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'], $_POST['otp'])) {
    $username = $_POST['username'];
    $enteredOTP = $_POST['otp'];
    $password = $_POST['password'];

    $sql = "SELECT otp FROM email_otp WHERE email = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedOTP = $row['otp'];
        
        if ($enteredOTP == $storedOTP) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateSql = "UPDATE users SET password = '$hashedPassword', otp = NULL WHERE username = '$username'";
            
            if ($conn->query($updateSql) === TRUE) {
                echo "Registration successful!";
                header("Location: login_form.php");
            } else {
                echo "Error updating record: " . $conn->error;
            }
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
    <title>Complete Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<?php include('nav.php'); ?>

<body>
    <h2>Complete Registration</h2>
    
    <form action="" method="post">
        <label for="username">User ID:</label>
        <input type="text" name="username" required><br>
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" required><br>
        <label for="password">Enter Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
