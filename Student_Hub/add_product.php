<?php
session_start();
// Include the database connection
require_once 'db.php';

// Initialize variables for error/success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['itemName'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $sellerID = $_SESSION['user_id']; // Use the currently logged-in user's ID from session

    // File upload handling
    $image = $_FILES['image'] ?? null;

    if (empty($itemName) || empty($description) || empty($price)) {
        $error = 'All fields are required.';
    } elseif ($image && $image['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'user_post/';
        $imageName = basename($image['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
            try {
                // Insert the post into the database
                $query = "INSERT INTO marketplace (ItemName, Description, Price, SellerID, CreatedAt, ImagePath) 
                          VALUES (:itemName, :description, :price, :sellerID, NOW(), :imagePath)";
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    ':itemName' => $itemName,
                    ':description' => $description,
                    ':price' => $price,
                    ':sellerID' => $sellerID,
                    ':imagePath' => $imageName
                ]);
                $success = 'Post added successfully.';
            } catch (PDOException $e) {
                $error = 'Error adding post: ' . $e->getMessage();
            }
        } else {
            $error = 'Failed to upload the image.';
        }
    } else {
        $error = 'Please upload an image.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-group button:hover {
            background: #0056b3;
        }
        .message {
            text-align: center;
            margin-bottom: 15px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add New Post</h1>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>
        <div class="form-group">
            <button type="submit">Add Post</button>
        </div>
    </form>
</div>

</body>
</html>