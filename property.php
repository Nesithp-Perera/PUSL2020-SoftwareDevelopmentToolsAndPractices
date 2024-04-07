<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/config.php';

// Fetch property details from the database
$property_id = $_GET['id'];
$sql = "SELECT p.*, u.name AS landlord_name FROM properties p JOIN users u ON p.landlord_id = u.id WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
$property = $result->fetch_assoc();

// Handle rental request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'student') {
    $student_id = $_SESSION['user_id'];
    $request_date = date('Y-m-d');

    $sql = "INSERT INTO requests (student_id, property_id, request_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $property_id, $request_date);

    if ($stmt->execute()) {
        $success_message = "Rental request sent successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}
?>
<?php
   $root_url = "http://".$_SERVER['HTTP_HOST'] . "/PUSL2020";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Property Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php include('includes/header.php'); ?>
        </div>
    </div>
</div>
<div class="container mt-5">
    <h2 class="text-center mb-4">Property Details</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <?php
                // Check if property image exists
                if (!empty($property['image_path'])) {
                    ?>
                    <img src="<?php echo $root_url ?><?php echo $property['image_path']; ?>" class="card-img-top" alt="Property Image">
                    <?php
                } 
                    ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $property['title']; ?></h5>
                        <p class="card-text"><strong>Description:</strong> <?php echo $property['description']; ?></p>
                        <p class="card-text"><strong>Address:</strong> <?php echo $property['address']; ?></p>
                        <p class="card-text"><strong>Rent:</strong> <?php echo $property['rent']; ?></p>
                        <p class="card-text"><strong>Landlord:</strong> <?php echo $property['landlord_name']; ?></p>
                        <?php if ($_SESSION['role'] === 'student') { ?>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $property['id']; ?>">
                                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                                <button type="submit" class="btn btn-primary">Send Rental Request</button>
                            </form>
                            <?php if (isset($success_message)) { ?>
                                <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                            <?php } ?>
                            <?php if (isset($error_message)) { ?>
                                <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php
                
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
