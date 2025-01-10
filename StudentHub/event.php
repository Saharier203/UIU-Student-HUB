<?php
include('db_config.php');

// Fetch events
$sql = "SELECT * FROM Events ORDER BY EventDate ASC";
$result = $conn->query($sql);

// Handle new event creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventName = $_POST['eventName'];
    $description = $_POST['description'];
    $eventDate = $_POST['eventDate'];
    $organizerID = 1; // Placeholder: Use session-based user ID

    $sql = "INSERT INTO Events (EventName, Description, EventDate, OrganizerID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $eventName, $description, $eventDate, $organizerID);

    if ($stmt->execute()) {
        header("Location: events.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events</title>
</head>
<body>
    <h1>Upcoming Events</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong><?php echo $row['EventName']; ?></strong><br>
                <?php echo $row['Description']; ?><br>
                Date: <?php echo $row['EventDate']; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Create a New Event</h2>
    <form method="POST">
        <input type="text" name="eventName" placeholder="Event Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="date" name="eventDate" required>
        <button type="submit">Create Event</button>
    </form>
</body>
</html>
