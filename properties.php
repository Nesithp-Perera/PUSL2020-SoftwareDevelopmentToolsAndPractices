<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/config.php';

// Fetch approved properties from the database
$sql = "SELECT p.*, u.name AS landlord_name FROM properties p JOIN users u ON p.landlord_id = u.id WHERE p.is_approved = 1";
$result = $conn->query($sql);
$properties = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Properties</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #map {
            height: 75vh;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container_fluid">
        <div class="row m-0">
            <div class="col-md-12">
                <?php
                include('includes/header.php');
                ?>
            </div>
        </div>
    </div>
    <div class="container_fluid m-5 ">
        <h2 class="text-center mb-4">View Properties</h2>
       

     
        <div class="row">
            <div class="col-md-8">
                <div id="map"></div>
            </div>
            <div class="col-md-4">
                <div class="list-group">
                    <?php foreach ($properties as $property) { ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="property-card">
                            <h5 class="mb-1"><?php echo $property['title']; ?></h5>
                            <p class="mb-1"><?php echo $property['address']; ?></p>
                            <p class="mb-1">Rent: <?php echo $property['rent']; ?></p>
                            <a href="property.php?id=<?php echo $property['id']; ?>" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        function initMap() {
            var map = L.map('map').setView([7.8731, 80.7718], 8); // Set the initial view to Sri Lanka
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var properties = <?php echo json_encode($properties); ?>;
          
            properties.forEach(function(property) {
                console.log(property)
                if (property.latitude && property.longitude) {
                    L.marker([property.latitude, property.longitude]).addTo(map)
                        .bindPopup(property.title);
                }
            });
        }

        initMap();
    </script>
</body>
</html>