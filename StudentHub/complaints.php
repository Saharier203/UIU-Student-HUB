<?php
include('db_config.php');

// Fetch complaints
$sql = "SELECT * FROM Complaints ORDER BY CreatedAt DESC";
$result = $conn->query($sql);

// Handle new complaint
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = 1; // Placeholder: Use session-based user ID
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    $sql = "INSERT INTO Complaints (UserID, Subject, Description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userID, $subject, $description);

    if ($stmt->execute()) {
        header("Location: complaints.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaints</title>
</head>
<body>
    <h1>Complaints</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo $row['Subject']; ?></strong><br>
                <?php echo $row['Description']; ?><br>
                Status: <?php echo $row['Status']; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>File a Complaint</h2>
    <form method="POST">
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit">Submit Complaint</button>
    </form>
</body>
</html>
