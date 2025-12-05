<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
</head>
<body>
    <div class="dashboard">
        <h2>Welcome Admin: <?php echo htmlspecialchars($_SESSION['admin']); ?></h2>
        <ul>
            <li><a href="manage_customers.php">Manage Customers</a></li>
            <li><a href="manage_plants.php">Manage Plants</a></li>
            <li><a href="manage_suppliers.php">Manage Suppliers</a></li>
            <li><a href="manage_orders.php">Manage Orders</a></li>
            <li><a href="sales_dashboard.php">sales</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</body>
</html>