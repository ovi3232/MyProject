<?php
session_start();
include 'db.php'; // Database connection

$error = "";

// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists in the database
    $sql = "SELECT * FROM Users WHERE Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        // Insert new user data into the database
        $sql = "INSERT INTO Users (Name, Email, Password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user'] = $email; // Store email in session
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .register-container { width: 350px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input[type=text], input[type=password], input[type=email] { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        input[type=submit] { width: 100%; padding: 10px; border: none; background: #28a745; color: #fff; font-size: 16px; border-radius: 5px; cursor: pointer; }
        input[type=submit]:hover { background: #218838; }
        .error-message { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Register">
        </form>

        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>

        <p style="text-align: center; margin-top: 10px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</body>
</html>
