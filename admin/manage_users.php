<?php
require_once '../includes/config.php';
session_start();

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Retrieve all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!-- HTML table to display all users -->
<div class="container_fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                include('../includes/header.php');
                ?>
            </div>
        </div>
    </div>
    <h2 class="text-center mt-4">Manage Users</h2>
<table class="table table-striped mt-5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td>
                    <?php if ($row['role'] !== 'admin') { ?>
                        <a href="delete_users.php?id=<?php echo $row['id']; ?>">Delete</a>
                    <?php } else { ?>
                        <span>Cannot delete admin</span>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>