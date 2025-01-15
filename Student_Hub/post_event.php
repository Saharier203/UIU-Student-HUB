<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_query = $conn->prepare("SELECT * FROM users WHERE UserID = :user_id");
$user_query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$user_query->execute();
$user = $user_query->fetch(PDO::FETCH_ASSOC);

// Handle event posting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_description = $_POST['event_description'];
    $organizer_contact = $_POST['organizer_contact'];

    // Handle file upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
    move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file);

    // Insert event into the database
    $event_query = $conn->prepare("INSERT INTO events (EventName, EventDate, Description, OrganizerContact, EventImage, OrganizerID) VALUES (:event_name, :event_date, :event_description, :organizer_contact, :event_image, :organizer_id)");
    $event_query->bindValue(':event_name', $event_name, PDO::PARAM_STR);
    $event_query->bindValue(':event_date', $event_date, PDO::PARAM_STR);
    $event_query->bindValue(':event_description', $event_description, PDO::PARAM_STR);
    $event_query->bindValue(':organizer_contact', $organizer_contact, PDO::PARAM_STR);
    $event_query->bindValue(':event_image', $target_file, PDO::PARAM_STR);
    $event_query->bindValue(':organizer_id', $user_id, PDO::PARAM_INT);
    $event_query->execute();

    header("Location: events.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Event</title>
    <link rel="stylesheet" href="css/event.css"> <!-- Link to event.css file -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>UIU Student Hub</h2>
                <h3>Welcome, <?php echo htmlspecialchars($user['FullName']); ?></h3>
            </div>
            <nav>
                <ul>
                    <li><a href="student_dashboard.php">Dashboard</a></li>
                    <li><a href="complaints.php">Your Complaints</a></li>
                    <li><a href="reservations.php">Room Reservations</a></li>
                    <li><a href="events.php">Upcoming Events</a></li>
                    <li><a href="lunchbox.php">Lunchbox Menu</a></li>
                    <li><a href="marketplace.php">Marketplace Items</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="main-content">
            <main>
                <section class="header-section">
                    <h2>Post an Event</h2>
                </section>
                <section>
                    <form method="POST" action="post_event.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="event_name">Event Name:</label>
                            <input type="text" id="event_name" name="event_name" required>
                        </div>
                        <div class="form-group">
                            <label for="event_date">Event Date:</label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        <div class="form-group">
                            <label for="event_description">Event Description:</label>
                            <textarea id="event_description" name="event_description" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="organizer_contact">Organizer Contact:</label>
                            <input type="text" id="organizer_contact" name="organizer_contact" required>
                        </div>
                        <div class="form-group">
                            <label for="event_image">Event Image:</label>
                            <input type="file" id="event_image" name="event_image" accept="image/*" required>
                        </div>
                        <button type="submit">Post Event</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>