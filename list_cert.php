<?php
session_start();
include('conn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Delete individual certificate
if (isset($_GET['delete_certificate'])) {
    $deleteCertificateId = $_GET['delete_certificate'];
    $deleteSql = "DELETE FROM certificates WHERE id = '$deleteCertificateId' AND user_id = '$user_id'";
    $conn->query($deleteSql);
    header("Location: list_cert.php");
    exit;
}

// Delete all certificates
if (isset($_POST['delete_all'])) {
    $deleteAllSql = "DELETE FROM certificates WHERE user_id = '$user_id'";
    $conn->query($deleteAllSql);
    header("Location: list_cert.php");
    exit;
}

// Fetch all certificates created by the logged-in user
$sql = "SELECT * FROM certificates WHERE user_id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Certificates</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include('nav.php'); ?>

<div class="container">
    <h2>Your Certificates</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <form method="post" action="list_cert.php">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Course Name</th>
                        <th>Date of Issue</th>
                        <th>Certificate Hash</th>
                        <th>Organization Name</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['coursename']; ?></td>
                            <td><?php echo $row['date_of_issue']; ?></td>
                            <td><?php echo $row['certificate_hash']; ?></td>
                            <td><?php echo $row['organization_name']; ?></td>
                            <td>
                                <a href="?delete_certificate=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" name="delete_all" onclick="return confirm('Are you sure you want to delete all certificates?')">Delete All</button>
        </form>
    <?php else: ?>
        <p>No certificates found.</p>
    <?php endif; ?>
</div>

</body>
</html>
