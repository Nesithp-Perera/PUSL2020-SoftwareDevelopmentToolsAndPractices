<?php
session_start();

// Check if the user is a landlord
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config.php';

// Fetch the landlord's properties
$landlord_id = $_SESSION['user_id'];
$sql = "SELECT * FROM properties WHERE landlord_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $landlord_id);
$stmt->execute();
$result = $stmt->get_result();
$properties = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Properties</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
</head>
<body>
<div class="container_fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                include('../includes/header.php');
                ?>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Manage Properties</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Address</th>
                    <th>Rent</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property) { ?>
                <tr>
                    <td><?php echo $property['title']; ?></td>
                    <td><?php echo $property['description']; ?></td>
                    <td><?php echo $property['address']; ?></td>
                    <td><?php echo $property['rent']; ?></td>
                    <td>
                        <?php if ($property['is_approved']) { ?>
                            <span class="badge badge-success">Approved</span>
                        <?php } elseif ($property['is_approved'] === 0) { ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php } else { ?>
                            <span class="badge badge-danger">Rejected</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="update_properties.php?id=<?php echo $property['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_property.php?id=<?php echo $property['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>