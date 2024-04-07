<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to the login page
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Welcome, <?php echo $role; ?>!</h2>
        <p>This is your dashboard.</p>
        <!-- Add additional content and functionality based on the user role -->
    </div>
</body>
</html>