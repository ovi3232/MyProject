<?php
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the order details
$order_id = $_GET['order_id']; // Fetch the order ID from the URL

$stmt = $conn->prepare("SELECT * FROM orders WHERE OrderID = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Fetch the plant details
$stmt = $conn->prepare("SELECT plants.Name, plants.Price, orderdetails.Quantity FROM orderdetails JOIN plants ON orderdetails.PlantID = plants.PlantID WHERE orderdetails.OrderID = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_details = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - GreenHeaven</title>
    <link rel="stylesheet" href="Css 2.css">
</head>
<body>

    <div class="order-confirmation">
        <h2>Order Successfully Placed!</h2>
        <p>Thank you for your order. It will be delivered to your address soon.</p>

        <h3>Order Details:</h3>
        <ul>
            <?php while ($order_item = $order_details->fetch_assoc()) { ?>
                <li>
                    <?php echo $order_item['Name']; ?> - Quantity: <?php echo $order_item['Quantity']; ?>, Price: $<?php echo $order_item['Price']; ?>
                </li>
            <?php } ?>
        </ul>

        <p><a href="user_dashboard.php">Go back to Dashboard</a></p>
    </div>

</body>
</html>
