<?php
session_start();

// Check if the user is a landlord
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'landlord') {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/config.php';

// Check if property ID is provided in the URL
if(isset($_GET['id'])) {
    $property_id = $_GET['id'];
    
    // Fetch the selected property
    $sql = "SELECT * FROM properties WHERE id = ? AND landlord_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $property_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_property = $result->fetch_assoc();
    
    // Redirect to manage properties if the property is not found
    if(!$selected_property) {
        header("Location: manage_properties.php");
        exit;
    }
} else {
    // Redirect to manage properties if property ID is not provided
    header("Location: manage_properties.php");
    exit;
}

// Handle property update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $address = $_POST['address'];
    $rent = $_POST['rent'];
    
    // Handle image upload
    if(isset($_FILES['property_image']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['property_image']['name'];
        $image_tmp = $_FILES['property_image']['tmp_name'];
        $image_destination = '../uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_destination);
    } else {
        $image_destination = null;
    }
    
    // Update the selected property
    $sql = "UPDATE properties 
            SET title = ?, description = ?, address = ?, rent = ?, image_path = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $description, $address, $rent, $image_destination, $property_id);
    
    if ($stmt->execute()) {
        $success_message = "Property updated successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Property</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container_fluid">
        <div class="row m-0">
            <div class="col-md-12">
                <?php
                include('../includes/header.php');
                ?>
            </div>
        </div>
    </div>
<div class="container mt-5">
    <h2 class="text-center mb-4">Update Property</h2>
    <div class="card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo $selected_property['title']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $selected_property['description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $selected_property['address']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="rent">Rent</label>
                    <input type="number" class="form-control" id="rent" name="rent" step="0.01" value="<?php echo $selected_property['rent']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="property_image">Property Image</label>
                    <input type="file" class="form-control-file" id="property_image" name="property_image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Update Property</button>
            </form>
        </div>
    </div>
    <?php if (isset($success_message)) { ?>
        <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
    <?php } ?>
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
    <?php } ?>
</div>
</body>
</html>
