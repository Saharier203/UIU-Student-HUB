<?php
require_once 'db.php'; // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $itemName = $_POST['itemName'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Assuming the seller's ID is stored in the session (make sure the user is logged in)
    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to add a product.";
        exit;
    }
    $sellerID = $_SESSION['user_id']; // The logged-in user's ID

    // Prepare the SQL query to insert the product into the database
    $sql = "INSERT INTO Marketplace (ItemName, Description, Price, SellerID) 
            VALUES (:itemName, :description, :price, :sellerID)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':itemName', $itemName);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':sellerID', $sellerID);

    // Execute the query
    if ($stmt->execute()) {
        echo "Product added successfully!";
        header("Location: marketplace.php"); // Redirect to marketplace after successful product addition
        exit();
    } else {
        echo "Error adding product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add a New Product</h1>
    <form method="POST" action="add_product.php">
        <!-- Item Name -->
        <label for="itemName">Item Name:</label><br>
        <input type="text" id="itemName" name="itemName" required><br><br>

        <!-- Description -->
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <!-- Price -->
        <label for="price">Price (BDT):</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <!-- Submit Button -->
        <button type="submit">Add Product</button>
    </form>

    <!-- Link back to Marketplace -->
    <a href="marketplace.php">Back to Marketplace</a>
</body>
</html>
