<?php
session_start();

// Check if the user is a warden
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warden') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config.php';

// Fetch all properties from the database
$sql = "SELECT p.*, u.name AS landlord_name FROM properties p JOIN users u ON p.landlord_id = u.id";
$result = $conn->query($sql);

// Handle property approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_id = $_POST['property_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $sql = "UPDATE properties SET is_approved = 1 WHERE id = ?";
    } else {
        $sql = "UPDATE properties SET is_approved = 0 WHERE id = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
}
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
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Address</th>
                            <th>Rent</th>
                            <th>Landlord</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['rent']; ?></td>
                                <td><?php echo $row['landlord_name']; ?></td>
                                <td>
                                    <?php
                                    if ($row['is_approved']) {
                                        echo '<span class="badge badge-success">Approved</span>';
                                    } else {
                                        echo '<span class="badge badge-warning">Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                        <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                                        <?php if ($row['is_approved']) { ?>
                                            <button type="submit" class="btn btn-danger" name="action" value="reject">Reject</button>
                                        <?php } else { ?>
                                            <button type="submit" class="btn btn-success" name="action" value="approve">Approve</button>
                                        <?php } ?>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>