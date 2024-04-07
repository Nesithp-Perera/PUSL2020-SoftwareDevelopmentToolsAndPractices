<?php
session_start();

// Check if the user is logged in and is a landlord
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    header("Location: login.php");
    exit;
}

require_once '../includes/config.php';

// Fetch the landlord's property requests from the database
$landlord_id = $_SESSION['user_id'];
$sql = "SELECT r.*, p.title, r.is_approved, u.name AS student_name
        FROM requests r
        JOIN properties p ON r.property_id = p.id
        JOIN users u ON r.student_id = u.id
        WHERE p.landlord_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $landlord_id);
$stmt->execute();
$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);

// Handle request approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $sql = "UPDATE requests SET is_approved = 1 WHERE id = (SELECT property_id FROM requests WHERE id = ?)";
    } elseif ($action === 'reject') {
        $sql = "UPDATE requests SET is_approved = -1 WHERE id = (SELECT property_id FROM requests WHERE id = ?)";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    if ($stmt->execute()) {
        $success_message = "Request has been " . ($action === 'approve' ? 'approved' : 'rejected') . " successfully.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rental Requests</title>
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
        <h2 class="text-center mb-4">Rental Requests</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Property Title</th>
                    <th>Student</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            
                <?php foreach ($requests as $request) { ?>
                <tr>
                    <td><?php echo $request['title']; ?></td>
                    <td><?php echo $request['student_name']; ?></td>
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
                    <td>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if (isset($success_message)) { ?>
        <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
        <?php } ?>
    </div>
</body>
</html>