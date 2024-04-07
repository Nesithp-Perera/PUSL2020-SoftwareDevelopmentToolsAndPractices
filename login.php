<?php
// Check if the user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    // User is already logged in, redirect based on their role
    $role = $_SESSION['role'];
    switch ($role) {
        case 'admin':
            header("Location: manage_users.php");
            exit;
        case 'landlord':
            header("Location: landlord/add_property.php");
            exit;
        case 'warden':
        case 'student':
            header("Location: properties.php");
            exit;
        default:
            // Handle other roles or edge cases
            break;
    }
}
// Include the login form
require_once 'index.php';