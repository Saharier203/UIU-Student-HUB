<?php
// Assuming a session-based login and user data retrieval, you can fetch user info from the database
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT * FROM users WHERE UserID = :user_id");
$user_query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$user_query->execute();
$user = $user_query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="css/profile.css">
</head>
<body>
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
</body>
</html>
