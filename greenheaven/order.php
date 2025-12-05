<?php
session_start();
include 'db.php'; // Include your database connection

$error = "";

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username']; // This could be Email or Username
    $password = $_POST['password']; // Password entered by the user

    // Check if it's an admin or user login
    if (isset($_POST['is_admin']) && $_POST['is_admin'] == "1") {
        // Admin login: Check if username/email exists in Admin table
        $stmt = $conn->prepare("SELECT Username, Password FROM Admin WHERE Email=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            // Compare plaintext passwords
            if ($password == $admin['Password']) {
                $_SESSION['admin'] = $username;
                header("Location: dashboard.php"); // Redirect to dashboard.php for Admin
                exit();
            } else {
                $error = "Invalid admin password!";
            }
        } else {
            $error = "Admin email not found!";
        }
    } else {
        // User login: Check if username/email exists in Users table
        $stmt = $conn->prepare("SELECT UserID, Username, Password FROM Users WHERE Email=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Compare plaintext passwords
            if ($password == $user['Password']) {
                $_SESSION['user_id'] = $user['UserID']; // Store the user ID in the session
                $_SESSION['username'] = $user['Username']; // Optionally, store the username in the session
                header("Location: dashboard.php"); // Redirect to dashboard after login
                exit();
            } else {
                $error = "Invalid user password!";
            }
        } else {
            $error = "User email not found!";
        }
    }

    $stmt->close();
}
?>
