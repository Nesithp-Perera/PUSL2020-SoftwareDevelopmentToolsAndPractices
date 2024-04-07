<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';

// Fetch the user's requests from the database
$student_id = $_SESSION['user_id'];
$sql = "SELECT r.*, p.title, r.is_approved 
        FROM requests r
        JOIN properties p ON r.property_id = p.id
        WHERE r.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rental Request Status</title>
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
        <h2 class="text-center mb-4">Rental Request Status</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Property Title</th>
                    <th>Request Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request) { ?>
                <tr>
                    <td><?php echo $request['title']; ?></td>
                    <td><?php echo $request['request_date']; ?></td>
                    <td>
                        <?php if ($request['is_approved']) { ?>
                            <span class="badge badge-success">Approved</span>
                        <?php } elseif ($request['is_approved'] === 0) { ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php } else { ?>
                            <span class="badge badge-danger">Rejected</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>