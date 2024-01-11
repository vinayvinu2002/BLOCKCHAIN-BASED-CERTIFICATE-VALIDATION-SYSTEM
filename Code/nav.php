<?php
// nav.php

// Do not start a new session if a session is already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <div class="container">
        <ul>
            <li><a href="index.php">Home</a></li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Display these options for logged-in users -->
                <li><a href="certificate_generator.php">Generate Certificate</a></li>
                <!-- <li><a href="certificate_search.php">Search Certificate</a></li> -->
                <li><a href="list_cert.php">List certificates</a></li>
                <li><a href="generate_bulk.php">Generate in bulk</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <!-- Display these options for unregistered users -->
                <li><a href="login_form.php">Login</a></li>
                <li><a href="register_form.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
