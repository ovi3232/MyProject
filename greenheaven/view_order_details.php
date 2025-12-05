<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_details = [];
$customer_info = [];
$total_amount = 0;

if ($order_id > 0) {
    // Fetch order details and customer info
    $stmt = $conn->prepare("
        SELECT 
            o.OrderID, o.OrderDate, o.TotalAmount,
            c.Name AS CustomerName, c.Email AS CustomerEmail, c.Phone AS CustomerPhone, c.Address AS CustomerAddress
        FROM Orders o
        JOIN Users c ON o.CustomerID = c.id
        WHERE o.OrderID = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $customer_info = $result->fetch_assoc();
        $total_amount = $customer_info['TotalAmount'];
    }
    $stmt->close();

    // Fetch plants in this order
    $stmt = $conn->prepare("
        SELECT 
            od.Quantity, od.UnitPrice,
            p.Name AS PlantName, p.Type AS PlantType
        FROM OrderDetails od
        JOIN Plants p ON od.PlantID = p.PlantID
        WHERE od.OrderID = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    header("Location: manage_orders.php"); // Redirect if no order_id provided
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
</head>
<body>
    <div class="dashboard">
        <h2>Order Details (ID: <?= htmlspecialchars($order_id) ?>)</h2>
        <p><a href="manage_orders.php">Back to Manage Orders</a></p>

        <?php if (!empty($customer_info)): ?>
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($customer_info['CustomerName']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($customer_info['CustomerEmail']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($customer_info['CustomerPhone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($customer_info['CustomerAddress']) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($customer_info['OrderDate']) ?></p>
            <p><strong>Total Amount:</strong> <?= htmlspecialchars(number_format($total_amount, 2)) ?> BDT</p>

            <h3>Plants in this Order</h3>
            <?php if (!empty($order_details)): ?>
                <table>
                    <tr>
                        <th>Plant Name</th>
                        <th>Type</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php foreach ($order_details as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['PlantName']) ?></td>
                            <td><?= htmlspecialchars($item['PlantType']) ?></td>
                            <td><?= htmlspecialchars(number_format($item['UnitPrice'], 2)) ?></td>
                            <td><?= htmlspecialchars($item['Quantity']) ?></td>
                            <td><?= htmlspecialchars(number_format($item['UnitPrice'] * $item['Quantity'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No plants found for this order.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="error-message">Order not found or invalid ID.</p>
        <?php endif; ?>
    </div>
</body>
</html>