<?php
// Check if session is not active, then start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
   $root_url = "http://".$_SERVER['HTTP_HOST'] . "/PUSL2020";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Property Rental System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $root_url ?>../css/styles.css">
</head>
<body>

    
    <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="header-left"> <img src="<?php echo $root_url ?>../images/nsbm_logo.png" alt="Property Rental System" width="100"  class="navbar-brand-logo">
        <br>
    <a class="navbar-brand" href="<?php echo $root_url ?>/index.php">Property Rental System</a> </div>
       
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/profile.php">Profile</a>
            </li>
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'student' || $_SESSION['role'] === 'warden')) { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/properties.php">Properties</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'warden') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/warden/manage_properties.php">Manage Properties</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/student/request_status.php">Request Status</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'landlord') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/landlord/add_property.php">Add Property</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/landlord/manage_propertis.php">Manage Properties</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/landlord/request_management.php">Manage Requests</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/admin/manage_users.php">Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $root_url ?>/admin/add_users.php">Add Users</a>
            </li>
            
            <?php } ?>
            <li class="nav-item">
                <a class="nav-link txt-bold" href="<?php echo $root_url ?>/logout.php">Logout</a>
            </li>
           
        </ul>
        <div class="right-txt-head"><?php 
        if (isset($_SESSION['role']))
        echo $_SESSION['role']
        
        ?></div>
    </div>
</nav>

</body>
</html>
