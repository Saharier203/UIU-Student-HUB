<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Database connection file

if (!isset($_GET['event_id'])) {
    header("Location: events.php");
    exit;
}

$event_id = $_GET['event_id'];

// Fetch event details
$event_query = $conn->prepare("SELECT * FROM events WHERE EventID = :event_id");
$event_query->bindValue(':event_id', $event_id, PDO::PARAM_INT);
$event_query->execute();
$event = $event_query->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}

// Fetch organizer details
$organizer_query = $conn->prepare("SELECT * FROM users WHERE UserID = :organizer_id");
$organizer_query->bindValue(':organizer_id', $event['OrganizerID'], PDO::PARAM_INT);
$organizer_query->execute();
$organizer = $organizer_query->fetch(PDO::FETCH_ASSOC);

if (!$organizer) {
    echo "Organizer not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['EventName']); ?></title>
    <link rel="stylesheet" href="css/event.css"> <!-- Link to new event.css file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Link to Font Awesome -->
</head>
<body>
    <div class="container">
        <div class="main-content">
            <main>
                <section class="header-section">
                    <h2><?php echo htmlspecialchars($event['EventName']); ?></h2>
                </section>
                <section class="event-details">
                    <?php if (!empty($event['EventImage'])): ?>
                        <img src="<?php echo htmlspecialchars($event['EventImage']); ?>" alt="Event Image" class="event-image">
                    <?php endif; ?>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['EventDate']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($event['Description']); ?></p>
                    <div class="organizer-info">
                        <?php if (!empty($organizer['ProfilePicture'])): ?>
                            <img src="<?php echo htmlspecialchars($organizer['ProfilePicture']); ?>" alt="Organizer Profile Picture" class="profile-picture">
                        <?php endif; ?>
                        <div>
                            <p class="organizer-name"><?php echo htmlspecialchars($organizer['FullName']); ?></p>
                            <p class="organizer-contact"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($event['OrganizerContact']); ?></p>
                        </div>
                    </div>
                    <br><br>
                    <button onclick="window.location.href='events.php'">Back to Events</button>
                </section>
            </main>
        </div>
    </div>
</body>
</html>