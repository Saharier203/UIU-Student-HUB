<?php
session_start();
require_once 'db.php';

// Initialize variables for error/success messages
$error = '';
$success = '';

// Check if user is logged in and if the post ID is set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $postID = $_GET['id'];
    $userID = $_SESSION['user_id'];

    // Fetch post details from the database
    $query = "SELECT * FROM marketplace WHERE ItemID = :postID AND SellerID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->execute([':postID' => $postID, ':userID' => $userID]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if post exists and belongs to the logged-in user
    if (!$post) {
        header("Location: marketplace.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $itemName = $_POST['itemName'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $image = $_FILES['image'] ?? null;
        $imagePath = $post['ImagePath']; // Keep the existing image if not updated

        // Validate input
        if (empty($itemName) || empty($description) || empty($price)) {
            $error = 'All fields are required.';
        } elseif (!is_numeric($price) || $price <= 0) {
            $error = 'Price must be a valid positive number.';
        } elseif ($image && $image['error'] === UPLOAD_ERR_OK) {
            // Validate image type and size
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($image['type'], $allowedTypes)) {
                $error = 'Only JPEG, PNG, and JPG files are allowed.';
            } elseif ($image['size'] > 1048576) { // 1MB limit
                $error = 'Image size must be less than 1MB.';
            } else {
                $targetDir = 'user_post/';
                $imageName = basename($image['name']);
                $imagePath = $targetDir . $imageName;

                // Move the uploaded file
                if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                    // Update post in the database
                    $query = "UPDATE marketplace SET ItemName = :itemName, Description = :description, 
                              Price = :price, ImagePath = :imagePath WHERE ItemID = :postID AND SellerID = :userID";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([
                        ':itemName' => $itemName,
                        ':description' => $description,
                        ':price' => $price,
                        ':imagePath' => $imagePath,
                        ':postID' => $postID,
                        ':userID' => $userID
                    ]);
                    $success = 'Post updated successfully.';
                } else {
                    $error = 'Failed to upload the image.';
                }
            }
        } else {
            // Update post without changing the image
            $query = "UPDATE marketplace SET ItemName = :itemName, Description = :description, 
                      Price = :price WHERE ItemID = :postID AND SellerID = :userID";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':itemName' => $itemName,
                ':description' => $description,
                ':price' => $price,
                ':postID' => $postID,
                ':userID' => $userID
            ]);
            $success = 'Post updated successfully.';
        }
    }
} else {
    header("Location: marketplace.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>

<div class="container">
    <h1>Edit Post</h1>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" value="<?= htmlspecialchars($post['ItemName']) ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($post['Description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($post['Price']) ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Upload New Image (Optional):</label>
            <input type="file" id="image" name="image" accept="image/*">
            <p>Current Image: <img src="user_post/<?= htmlspecialchars($post['ImagePath']) ?>" alt="Current Image" width="100"></p>
        </div>
        <div class="form-group">
            <button type="submit">Update Post</button>
        </div>
    </form>
</div>

</body>
</html>
