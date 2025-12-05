<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message = "";

// Add Customer
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Use prepared statements for insertion
    $stmt = $conn->prepare("INSERT INTO Users (Name, Email, Phone, Address) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    if($stmt->execute()){
        $message = "Customer added successfully!";
    } else {
        $message = "Error adding customer: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Customer
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    // Use prepared statements for deletion
    $stmt = $conn->prepare("DELETE FROM Users WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $message = "Customer deleted successfully!";
    } else {
        $message = "Error deleting customer: " . $stmt->error;
    }
    $stmt->close();
    header("Location: manage_customers.php"); // Redirect to refresh the page after deletion
    exit();
}

// Fetch Customers
$result = $conn->query("SELECT * FROM Users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
    <style>
        /* Dashboard container */
        .dashboard {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        /* Responsiveness: Add horizontal scroll for small screens */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        /* Input fields styling */
        form input {
            padding: 8px;
            margin: 10px 0;
            width: calc(100% - 16px); /* Full width with padding */
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Success or error message */
        .error-message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }

        /* Link styling */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <h2>Manage Customers</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>

        <?php if(!empty($message)) echo "<p class='error-message' style='color:green;'>$message</p>"; ?>

        <form method="post" action="manage_customers.php">
            Name:<input type="text" name="name" required>
            Email:<input type="email" name="email" required>
            Phone:<input type="text" name="phone" required>
            Address:<input type="text" name="address" required>
            <input type="submit" name="add" value="Add Customer">
        </form>

        <h3>Existing Customers</h3>
        <table>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Action</th></tr>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){ 
            ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td><?= htmlspecialchars($row['Email']) ?></td>
                <td><?= htmlspecialchars($row['Phone']) ?></td>
                <td><?= htmlspecialchars($row['Address']) ?></td>
                <td><a href="manage_customers.php?delete=<?= htmlspecialchars($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a></td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6'>No customers found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>