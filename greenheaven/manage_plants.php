<?php
session_start();
include 'db.php';
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

$message = "";

// Add Plant
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO Plants (Name, Type, Price, QuantityInStock) VALUES (?,?,?,?)");
    $stmt->bind_param("ssdi", $name, $type, $price, $qty); // 'd' for double (decimal)
    if($stmt->execute()){
        $message = "Plant added successfully!";
    } else {
        $message = "Error adding plant: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Plant
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM Plants WHERE PlantID=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $message = "Plant deleted successfully!";
    } else {
        $message = "Error deleting plant: " . $stmt->error;
    }
    $stmt->close();
    header("Location: manage_plants.php");
    exit();
}

// Fetch Plants
$result = $conn->query("SELECT * FROM Plants");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Plants - Green Heaven</title>
    <link rel="stylesheet" href="./Css 2.css">
</head>
<body>
    <div class="dashboard">
        <h2>Manage Plants</h2>
        <p><a href="dashboard.php">Back to Dashboard</a></p>

        <?php if(!empty($message)) echo "<p class='error-message' style='color:green;'>$message</p>"; ?>

        <form method="post" action="manage_plants.php">
            Name:<input type="text" name="name" required>
            Type:<input type="text" name="type" required>
            Price:<input type="number" step="0.01" name="price" required>
            Quantity:<input type="number" name="quantity" required>
            <input type="submit" name="add" value="Add Plant">
        </form>

        <h3>Existing Plants</h3>
        <table>
            <tr><th>ID</th><th>Name</th><th>Type</th><th>Price</th><th>Qty</th><th>Action</th></tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?= htmlspecialchars($row['PlantID']) ?></td>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= htmlspecialchars($row['Type']) ?></td>
                    <td><?= htmlspecialchars($row['Price']) ?></td>
                    <td><?= htmlspecialchars($row['QuantityInStock']) ?></td>
                    <td><a href="manage_plants.php?delete=<?= htmlspecialchars($row['PlantID']) ?>" onclick="return confirm('Are you sure you want to delete this plant?');">Delete</a></td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6'>No plants found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>