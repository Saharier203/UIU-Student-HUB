<?php
// student_dashboard.php
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

// Fetch other data (complaints, reservations, events, etc.)
$complaints_query = $conn->prepare("SELECT * FROM complaints WHERE UserID = :user_id");
$complaints_query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$complaints_query->execute();
$complaints_result = $complaints_query->fetchAll(PDO::FETCH_ASSOC);

// More queries here...

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="css/style3.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>UIU Student Hub</h2>
            </div>
            <nav>
                <ul>
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
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($user['FullName']); ?></h1>
            </header>
            <main>
                <section>
                    <h2>Your Complaints</h2>
                    <button onclick="window.location.href='complaints.php'">View Complaints</button>
                </section>

                <section>
                    <h2>Your Room Reservations</h2>
                    <button onclick="window.location.href='reservations.php'">View Reservations</button>
                </section>

                <section>
                    <h2>Upcoming Events</h2>
                    <button onclick="window.location.href='events.php'">View Events</button>
                </section>

                <section>
                    <h2>Lunchbox Menu</h2>
                    <button onclick="window.location.href='lunchbox.php'">View Menu</button>
                </section>

                <section>
                    <h2>Marketplace Items</h2>
                    <button onclick="window.location.href='marketplace.php'">View Marketplace</button>
                </section>
            </main>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> UIU Student Hub</p>
    </footer>
</body>
</html>
