<?php
include('db_config.php');
$sql = "SELECT * FROM Marketplace WHERE BuyerID IS NULL";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marketplace</title>
</head>
<body>
    <h1>Marketplace</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><?php echo $row['ItemName'] . " - " . $row['Price'] . " BDT"; ?></li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
