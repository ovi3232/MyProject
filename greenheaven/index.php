<?php
session_start();

// Check if the admin is already logged in
if (isset($_SESSION['admin'])) {
    // If logged in, redirect to the dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>