<?php
session_start();

// Include database connection
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the sales data for the last month
$query = "
    SELECT p.Name, SUM(od.Quantity) AS total_sold
    FROM orderdetails od
    JOIN orders o ON od.OrderID = o.OrderID
    JOIN plants p ON od.PlantID = p.PlantID
    WHERE o.OrderDate >= CURDATE() - INTERVAL 1 MONTH
    GROUP BY p.Name
    ORDER BY total_sold DESC
";
$salesResult = mysqli_query($conn, $query);

$products = [];
$sales = [];

while ($row = mysqli_fetch_assoc($salesResult)) {
    $products[] = $row['Name'];
    $sales[] = $row['total_sold'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard - GreenHeaven</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Css 2.css">
    <style>
        .dashboard {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .chart-container {
            width: 80%;
            margin: 0 auto;
            padding: 30px 0;
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <p><a href="dashboard.php">Back to Dashboard</a></p>
        <h2>Sales Data for Last Month</h2>
        
        <!-- Chart Container -->
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
        
        <h3>Best Selling Products</h3>
        <ul>
            <?php foreach ($products as $index => $product) { ?>
                <li><?php echo $product; ?> - <?php echo $sales[$index]; ?> units sold</li>
            <?php } ?>
        </ul>
    </div>

    <script>
        // Prepare data for the chart
        const products = <?php echo json_encode($products); ?>;
        const sales = <?php echo json_encode($sales); ?>;
        
        // Chart.js configuration
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar', // You can also use 'line', 'pie', etc.
            data: {
                labels: products, // X-axis: product names
                datasets: [{
                    label: 'Total Units Sold',
                    data: sales, // Y-axis: total sales quantity
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
