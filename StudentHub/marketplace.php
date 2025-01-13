<?php
require_once 'db.php'; // Include the database connection

// Fetch all products with no BuyerID (available for sale)
$sql = "SELECT * FROM Marketplace WHERE BuyerID IS NULL";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="css/marketplace.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <header>
        <div class="logo">
            <h1>Marketplace</h1>
        </div>
        <nav>
            <a href="student_dashboard.php">Home</a>
            <a href="create_post.php">Create Post</a>
        </nav>
    </header>

    <main>
        <section class="product-list">
            <h2>Available Products</h2>
            <ul>
                <?php 
                // Loop through and display each product
                while ($row = $result->fetch(PDO::FETCH_ASSOC)): 
                    // Get the SellerID
                    $sellerID = $row['SellerID'];
                    
                    // Fetch the seller's FullName and Mobile from the Users table
                    $sellerQuery = "SELECT FullName, Mobile FROM Users WHERE UserID = :sellerID LIMIT 1"; 
                    $stmt = $conn->prepare($sellerQuery);
                    $stmt->bindParam(':sellerID', $sellerID, PDO::PARAM_INT);
                    $stmt->execute();
                    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Display the seller's full name and mobile number
                    $sellerFullName = $seller['FullName'];
                    $sellerMobile = $seller['Mobile'];
                ?>
                    <li class="product-item">
                        <div class="product-info">
                            <h3><?php echo $row['ItemName']; ?></h3>
                            <p><strong>Description:</strong> <?php echo $row['Description']; ?></p>
                            <p><strong>Price:</strong> <?php echo $row['Price']; ?> BDT</p>
                            <p><strong>Seller Name:</strong> <?php echo $sellerFullName; ?></p>
                            <p><strong>Seller's Mobile:</strong> <?php echo $sellerMobile; ?></p> <!-- Added Mobile -->
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Marketplace. All rights reserved.</p>
    </footer>
</body>
</html>
