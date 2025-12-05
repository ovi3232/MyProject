<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Fetch Orders
$sql = "
    SELECT 
        o.OrderID, 
        c.Name AS CustomerName, 
        o.TotalAmount, 
        o.OrderDate
    FROM Orders o
    JOIN Users c ON o.CustomerID = c.id
    ORDER BY o.OrderDate DESC
";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
</head>
<body>
    <div class="dashboard">
        <h2>Manage Orders</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>

        <h3>All Customer Orders</h3>
        <table>
            <tr><th>Order ID</th><th>Customer</th><th>Total Amount</th><th>Date</th><th>Action</th></tr>
            <?php 
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){ 
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['OrderID']) ?></td>
                    <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                    <td><?= htmlspecialchars(number_format($row['TotalAmount'], 2)) ?> BDT</td>
                    <td><?= htmlspecialchars($row['OrderDate']) ?></td>
                    <td><a href="view_order_details.php?order_id=<?= htmlspecialchars($row['OrderID']) ?>">View Details</a></td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='5'>No orders found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>