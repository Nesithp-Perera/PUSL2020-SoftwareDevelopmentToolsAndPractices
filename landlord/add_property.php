<?php
session_start();

// Check if the user is a landlord
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $rent = $_POST['rent'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $landlord_id = $_SESSION['user_id'];

    // Handle image upload
    $image_name = $_FILES['property_image']['name'];
    $image_tmp = $_FILES['property_image']['tmp_name'];
    $image_error = $_FILES['property_image']['error'];

    if ($image_error == 0) {
        $image_destination = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_destination);
    } else {
        $image_destination = null;
    }
    $sql = "INSERT INTO properties (landlord_id, title, description, address, rent, latitude, longitude, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssds", $landlord_id, $title, $description, $address, $rent, $latitude, $longitude, $image_destination);

    if ($stmt->execute()) {
        $success = "Property added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Property</title>
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
        <h2 class="text-center mb-4">Add Property</h2>
        <?php if (isset($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="rent">Rent</label>
                        <input type="number" class="form-control" id="rent" name="rent" step="0.01" required>
                    </div>
                    <h5>Please enter location details from google map</h5>
                    <div class="form-group">
                        <label for="latitude">latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude">
                    </div>
                    <div class="form-group">
                        <label for="longitude">longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude">
                    </div>

                    <div class="form-group">
                        <label for="property_image">Property Image</label>
                        <input type="file" class="form-control-file" id="property_image" name="property_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Property</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>