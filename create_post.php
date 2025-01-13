<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the form data
    $itemName = $_POST['itemName'];
    $price = $_POST['price'];

    // Prepare and execute the SQL query to insert the new item
    $sql = "INSERT INTO Marketplace (ItemName, Price) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $itemName, $price);

    if ($stmt->execute()) {
        echo "New item posted successfully!";
        header("Location: marketplace.php"); // Redirect back to marketplace after posting
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a New Post</h1>
    <form method="POST" action="create_post.php">
        <label for="itemName">Item Name:</label><br>
        <input type="text" id="itemName" name="itemName" required><br><br>
        
        <label for="price">Price (BDT):</label><br>
        <input type="text" id="price" name="price" required><br><br>
        
        <button type="submit">Post Item</button>
    </form>
    <a href="marketplace.php">Back to Marketplace</a>
</body>
</html>
