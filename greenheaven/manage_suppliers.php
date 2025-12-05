<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message = "";

// Add Supplier
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO Suppliers (Name, Phone, Email, Address) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $address);
    if($stmt->execute()){
        $message = "Supplier added successfully!";
    } else {
        $message = "Error adding supplier: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Supplier
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM Suppliers WHERE SupplierID=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $message = "Supplier deleted successfully!";
    } else {
        $message = "Error deleting supplier: " . $stmt->error;
    }
    $stmt->close();
    header("Location: manage_suppliers.php");
    exit();
}

// Fetch Suppliers
$result = $conn->query("SELECT * FROM Suppliers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Suppliers - Green Heaven</title>
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
        <h2>Manage Suppliers</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>

        <?php if(!empty($message)) echo "<p class='error-message' style='color:green;'>$message</p>"; ?>

        <form method="post" action="manage_suppliers.php">
            Name:<input type="text" name="name" required>
            Phone:<input type="text" name="phone" required>
            Email:<input type="email" name="email" required>
            Address:<input type="text" name="address" required>
            <input type="submit" name="add" value="Add Supplier">
        </form>

        <h3>Existing Suppliers</h3>
        <table>
            <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Action</th></tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?= htmlspecialchars($row['SupplierID']) ?></td>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= htmlspecialchars($row['Phone']) ?></td>
                    <td><?= htmlspecialchars($row['Email']) ?></td>
                    <td><?= htmlspecialchars($row['Address']) ?></td>
                    <td><a href="manage_suppliers.php?delete=<?= htmlspecialchars($row['SupplierID']) ?>" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a></td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6'>No suppliers found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>