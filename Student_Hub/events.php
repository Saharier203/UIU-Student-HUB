<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Database connection file

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_query = $conn->prepare("SELECT * FROM users WHERE UserID = :user_id");
$user_query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$user_query->execute();
$user = $user_query->fetch(PDO::FETCH_ASSOC);

// Check if user data is fetched correctly
if (!$user) {
    echo "User data not found.";
    exit;
}

// Fetch upcoming events from the database
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$current_date = date('Y-m-d');
$events_query = $conn->prepare("SELECT * FROM events WHERE EventName LIKE :search_query AND EventDate >= :current_date");
$events_query->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
$events_query->bindValue(':current_date', $current_date, PDO::PARAM_STR);
$events_query->execute();
$events = $events_query->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="css/event.css"> <!-- Link to new event.css file -->
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
                    <h2>Upcoming Events</h2>
                </section>
                <section class="controls">
                    <form method="GET" action="events.php" class="search-form">
                        <input type="text" name="search" placeholder="Search by event name" value="<?php echo htmlspecialchars($search_query); ?>">
                        <button type="submit" class="search-button">Search</button>
                    </form>
                    <button class="post-event-button" onclick="window.location.href='post_event.php'">Post an Event</button>
                </section>
                <?php if (!empty($events)): ?>
                    <ul>
                        <?php foreach ($events as $event): ?>
                            <li class="event-item">
                                <div class="event-header">
                                    <h3><?php echo htmlspecialchars($event['EventName']); ?></h3>
                                </div>
                                <p><?php echo htmlspecialchars($event['EventDate']); ?></p>
                                <p><?php echo htmlspecialchars($event['Description']); ?></p>
                                <?php if (!empty($event['EventImage'])): ?>
                                    <img src="<?php echo htmlspecialchars($event['EventImage']); ?>" alt="Event Image" style="max-width: 200px; max-height: 200px;">
                                <?php endif; ?>
                                <div class="see-more-container">
                                    <button class="see-more-button" onclick="window.location.href='event_details.php?event_id=<?php echo $event['EventID']; ?>'">Click here to see more</button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No upcoming events.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 UIU Student Hub</p>
    </footer>
</body>
</html>