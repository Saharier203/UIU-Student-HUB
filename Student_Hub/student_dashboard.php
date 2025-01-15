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
    <link rel="stylesheet" href="css/student_dashboard.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>UIU Student Hub</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="">Your Profile</a></li>
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
            <div class="profile-container">
                <!-- Cover Photo -->
                <div class="cover-photo" style="background-image: url('images/cover.jpg');"></div>

                <!-- Profile Details -->
                <div class="profile-details">
                    <!-- Profile Picture -->
                    <img src="<?php echo $user['ProfilePicture'] ?: 'images/default-profile.jpg'; ?>" alt="Profile Photo" class="profile-photo">

                    <!-- User Details -->
                    <h1 class="name"><?php echo htmlspecialchars($user['FullName']); ?></h1>
                    <p class="username"><?php echo htmlspecialchars($user['Email']); ?></p>

                    <!-- Additional Fields -->
                    <p class="mobile"><strong>Mobile:</strong> <?php echo htmlspecialchars($user['Mobile']); ?></p>
                    <p class="department"><strong>Department:</strong> <?php echo htmlspecialchars($user['Department']); ?></p>
                    <p class="work"><strong>Work:</strong> <?php echo htmlspecialchars($user['Work']); ?></p>
                    <p class="university"><strong>University:</strong> <?php echo htmlspecialchars($user['University']); ?></p>

                    <!-- Manage Profile Button -->
                    <a href="manage_profile.php">
                        <button class="manage-profile-btn">Manage Your Profile</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> UIU Student Hub</p>
    </footer>
</body>
</html>