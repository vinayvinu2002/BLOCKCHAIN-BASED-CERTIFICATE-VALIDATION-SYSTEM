<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title><link rel="stylesheet" href="styles.css">

</head>
<body>
<?php include('nav.php'); ?>

    <h2>User Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="register_form.php">Register here</a>.</p>
</body>
</html>
