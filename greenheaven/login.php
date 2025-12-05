<?php
session_start(); // Start the session

// Include database connection
include 'db.php'; // This will include your db connection from db.php

// Initialize error message
$error = "";

// Handle login form submission
if (isset($_POST['login'])) {
    $username = $_POST['username']; // Username or Email entered by the user
    $password = $_POST['password']; // Password entered by the user

    // Check if it's an admin or user login
    if (isset($_POST['is_admin']) && $_POST['is_admin'] == "1") {
        // Admin login: Check if username exists in Admin table
        $query = "SELECT * FROM Admin WHERE Username = '$username' AND Password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['admin'] = $username; // Store admin username in session
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            $error = "Invalid admin username or password!";
        }
    } else {
        // User login: Check if username (email) exists in Users table
        $query = "SELECT * FROM Users WHERE Email = '$username' AND Password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['user'] = $username; // Store user username in session
            header("Location: user_dashboard.php"); // Redirect to user dashboard
            exit();
        } else {
            $error = "Invalid user email or password!";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container { width: 350px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        input[type=submit] { width: 100%; padding: 10px; border: none; background: #28a745; color: #fff; font-size: 16px; border-radius: 5px; cursor: pointer; }
        input[type=submit]:hover { background: #218838; }
        .error-message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container" style="background: #c1c1c1ff;">
        <h2>Grean Heaven</h2>
        <p style="text-align: center;">A Plant Nursery Management System</p>
    </div>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Email or Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>

            <!-- Add radio buttons to select login type (Admin/User) -->
            <label>
                <input type="radio" name="is_admin" value="1" checked> Admin Login
            </label>
            <label>
                <input type="radio" name="is_admin" value="0"> User Login
            </label>

            <input type="submit" name="login" value="Login">
        </form>

        <p style="text-align: center; margin-top: 10px;">Don't have an account? <a href="register.php">Register here</a><br>

        <!-- Display error message if login fails -->
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
