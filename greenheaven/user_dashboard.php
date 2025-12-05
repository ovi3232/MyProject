<?php
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userEmail = $_SESSION['user'];
$query = "SELECT * FROM Users WHERE Email = '$userEmail'";
$userDetails = mysqli_query($conn, $query);
$userDetails = mysqli_fetch_assoc($userDetails);
$userID = $userDetails['id'];

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order'])) {
    $plantID = $_POST['plant_id'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $userID = $userDetails['id']; // Assuming user ID is stored in session

    // Fetch plant details
    $query = "SELECT Price, QuantityInStock FROM plants WHERE PlantID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $plantID);
    $stmt->execute();
    $result = $stmt->get_result();
    $plant = $result->fetch_assoc();

    if ($plant['QuantityInStock'] >= $quantity) {
        // Insert the order into the database
        $totalAmount = $plant['Price'] * $quantity;

        // Using simple SQL queries without prepared statements
        $orderQuery = "INSERT INTO orders (CustomerID, TotalAmount, PaymentMethod, Address, Phone) 
                    VALUES ('$userID', '$totalAmount', 'COD', '$address', '$phone')";

        // Execute the query
        if (mysqli_query($conn, $orderQuery)) {
            $orderID = mysqli_insert_id($conn); // Get the inserted order ID

            // Insert the order details
            $orderDetailQuery = "INSERT INTO orderdetails (OrderID, PlantID, Quantity, UnitPrice) 
                                VALUES ('$orderID', '$plantID', '$quantity', '{$plant['Price']}')";

            if (mysqli_query($conn, $orderDetailQuery)) {
                // Update the stock quantity
                $updateStockQuery = "UPDATE plants SET QuantityInStock = QuantityInStock - $quantity WHERE PlantID = $plantID";
                if (mysqli_query($conn, $updateStockQuery)) {
                    echo "<script>showNotification('Order placed successfully! Cash on Delivery as the payment method.', 'success');</script>";
                } else {
                    echo "<script>showNotification('Error updating stock.', 'failure');</script>";
                }
            } else {
                echo "<script>showNotification('Error inserting order details.', 'failure');</script>";
            }
        } else {
            echo "<script>showNotification('Error placing order.', 'failure');</script>";
        }

    } else {
        echo "<script>showNotification('Not enough stock available.', 'failure');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - GreenHeaven</title>
    <link rel="stylesheet" href="Css 2.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Dashboard Container */
        .dashboard {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Welcome Message */
        .dashboard h2 {
            text-align: center;
            color: #333;
        }

        /* Plants List Section */
        .plants-list {
            display: flex;
            flex-wrap: wrap;  /* Allow items to wrap on smaller screens */
            gap: 20px; /* Add space between each item */
            justify-content: space-between;
        }

        /* Individual Plant Item (Floating Block) */
        .plant-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            width: calc(33.333% - 20px);  /* Each plant takes 1/3 of the width, minus gap */
            box-sizing: border-box; /* Ensure padding is included in width calculation */
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        /* Hover Effect */
        .plant-item:hover {
            transform: translateY(-10px);  /* Slight lift effect on hover */
        }

        /* Plant Item Title */
        .plant-item h4 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        /* Plant Item Description (Price and Stock) */
        .plant-item p {
            font-size: 16px;
            margin: 5px 0;
        }

        /* Form Inside Plant Item */
        .plant-item form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Input Fields */
        .plant-item input[type="number"],
        .plant-item input[type="text"] {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .plant-item input[type="submit"] {
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .plant-item input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Logout Button */
        .logout-btn {
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Notification Styles (Top-center) */
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            color: white;
            display: none;  /* Hidden by default */
            max-width: 300px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        /* Success message (green) */
        .notification.success {
            background-color: #28a745;
        }

        /* Failure message (red) */
        .notification.failure {
            background-color: #dc3545;
        }

        /* Cart Button */
        .cart-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 1000;
        }

        /* Sliding cart menu */
        .cart-menu {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: #f8f9fa;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease-in-out;
            padding: 20px;
            overflow-y: auto;
        }

        .cart-menu h3 {
            margin-top: 0;
        }

        .close-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            position: absolute;
            top: 20px;
            left: 10px;
        }

    </style>
</head>
<body>

    <!-- Notification for success or failure -->
    <div id="notification" class="notification"></div>

    <div class="dashboard">
        <h2>Welcome <?php echo $userDetails['Name'] ?></h2>
        <p>Here you can browse and order plants.</p>

        <h3>Available Plants</h3>

        <div class="plants-list">
            <?php
            // Fetch available plants
            $query = "SELECT * FROM Plants";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='plant-item'>";
                echo "<h4>{$row['Name']}</h4>";
                echo "<p>Price: {$row['Price']}</p>";
                echo "<p>Stock: {$row['QuantityInStock']}</p>";
                ?>
                <!-- Order form for each plant -->
                <form action="" method="POST">
                    <input type="hidden" name="plant_id" value="<?php echo $row['PlantID']; ?>">
                    <input type="number" name="quantity" min="1" max="<?php echo $row['QuantityInStock']; ?>" required placeholder="Quantity">
                    <input type="text" name="address" required placeholder="Your Address">
                    <input type="text" name="phone" required placeholder="Your Phone Number">
                    <input type="submit" name="order" value="Order Now" class="order-btn">
                </form>
                <?php
                echo "</div>";
            }
            ?>
        </div>

        <!-- Logout button -->
        <form action="logout.php" method="POST">
            <input type="submit" value="Logout" class="logout-btn">
        </form>
    </div>

    <!-- Cart Button -->
    <button id="cart-btn" class="cart-btn">Orders</button>

    <!-- Cart Menu -->
    <div id="cart-menu" class="cart-menu">
        <h3>Your Orders</h3>
        <ul>
            <?php
            // Fetch user's orders from the database
            $query = "SELECT o.OrderID, o.TotalAmount, o.OrderDate, p.Name AS PlantName, od.Quantity 
                      FROM orders o 
                      JOIN orderdetails od ON o.OrderID = od.OrderID 
                      JOIN plants p ON od.PlantID = p.PlantID 
                      WHERE o.CustomerID = '$userID'";

            $ordersResult = mysqli_query($conn, $query);

            // Display orders
            while ($order = mysqli_fetch_assoc($ordersResult)) {
                echo "<div style='background-color: #c1c1c1ff; border-bottom:1px solid #ccc; margin-bottom:5px; padding:10px;'>";
                echo "<li>";
                echo "Order ID: {$order['OrderID']}<br>";
                echo "Plant: {$order['PlantName']}<br>";
                echo "Quantity: {$order['Quantity']}<br>";
                echo "Total: $ {$order['TotalAmount']}<br>";
                echo "Date: {$order['OrderDate']}";
                echo "</li>";
                echo "</div>";
            }
            ?>
        </ul>
    </div>

    <!-- JavaScript for notifications and cart -->
    <script>
        function showNotification(message, type) {
            var notification = document.getElementById('notification');
            notification.classList.add(type);
            notification.textContent = message;
            notification.style.display = 'block';

            setTimeout(function() {
                notification.style.opacity = 0;
                setTimeout(function() {
                    notification.style.display = 'none';
                    notification.style.opacity = 1;  // Reset opacity for next use
                }, 500);
            }, 5000);
        }

        let cartVisible = false;
        const cartBtn = document.getElementById('cart-btn');
        const cartMenu = document.getElementById('cart-menu');
        const closeBtn = document.getElementById('close-cart-btn');

        // Toggle cart visibility
        cartBtn.addEventListener('click', function() {
            cartVisible = !cartVisible;
            cartMenu.style.right = cartVisible ? '0' : '-300px';
            cartBtn.innerHTML = cartVisible ? 'Close' : 'Orders';
        });

        // Close cart menu
        closeBtn.addEventListener('click', function() {
            cartVisible = false;
            cartMenu.style.right = '-300px';
            cartBtn.innerHTML = 'Orders';
        });
    </script>

</body>
</html>
