<?php
session_start();
require 'db.php'; // Database connection

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

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['FullName'];
    $email = $_POST['Email'];
    $mobile = $_POST['Mobile'];  // New field
    $department = $_POST['Department'];  // New field
    $work = $_POST['Work'];  // New field
    $university = $_POST['University'];  // New field
    $errors = [];

    // Validate inputs
    if (empty($fullName) || empty($email)) {
        $errors[] = "All fields are required.";
    }

    // Handle profile picture upload (optional)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_picture'];
        $fileTmp = $file['tmp_name'];
        $fileName = uniqid() . "_" . basename($file['name']);
        $uploadDir = 'profile_pic';  // Ensure this directory exists
        $filePath = $uploadDir . $fileName;

        // Check if the file is an image (JPEG or PNG)
        $fileMime = mime_content_type($fileTmp);
        if ($fileMime !== 'image/jpeg' && $fileMime !== 'image/png') {
            $errors[] = "Only JPG or PNG images are allowed.";
        }

        // Check file size (Max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = "File size should not exceed 2MB.";
        }

        // If there are no errors, upload the file
        if (empty($errors) && move_uploaded_file($fileTmp, $filePath)) {
            // Update the profile picture path in the database
            $updateQuery = "UPDATE users SET FullName = :FullName, Email = :Email, Mobile = :Mobile, Department = :Department, Work = :Work, University = :University, ProfilePicture = :ProfilePicture WHERE UserID = :user_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindValue(':FullName', $fullName, PDO::PARAM_STR);
            $stmt->bindValue(':Email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':Mobile', $mobile, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':Department', $department, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':Work', $work, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':University', $university, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':ProfilePicture', $filePath, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: student_dashboard.php"); // Redirect back to dashboard
            exit;
        }
    } else {
        // If no picture uploaded, just update the name, email, and new fields
        if (empty($errors)) {
            $updateQuery = "UPDATE users SET FullName = :FullName, Email = :Email, Mobile = :Mobile, Department = :Department, Work = :Work, University = :University WHERE UserID = :user_id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindValue(':FullName', $fullName, PDO::PARAM_STR);
            $stmt->bindValue(':Email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':Mobile', $mobile, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':Department', $department, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':Work', $work, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':University', $university, PDO::PARAM_STR);  // Bind new fields
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: student_dashboard.php"); // Redirect back to dashboard
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
    <link rel="stylesheet" href="css/manage_profile.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>UIU Student Hub</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="student_dashboard.php">Home</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="main-content">
            <header>
                <h1>Manage Your Profile</h1>
            </header>

            <main>
                <!-- Display errors if any -->
                <?php if (!empty($errors)): ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="profile-info">
                    <?php if ($user['ProfilePicture']): ?>
                        <img src="<?php echo $user['ProfilePicture']; ?>" alt="Profile Picture" width="150" height="150">
                    <?php else: ?>
                        <img src="uploads/profile_pics/default.png" alt="Default Profile Picture" width="150" height="150">
                    <?php endif; ?>
                </div>

                <!-- Profile Update Form -->
                <form action="manage_profile.php" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="FullName">Full Name:</label>
                        <input type="text" id="FullName" name="FullName" value="<?php echo htmlspecialchars($user['FullName']); ?>" required>
                    </div>

                    <div>
                        <label for="Email">Email:</label>
                        <input type="email" id="Email" name="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                    </div>

                    <div>
                        <label for="Mobile">Mobile:</label>
                        <input type="text" id="Mobile" name="Mobile" value="<?php echo htmlspecialchars($user['Mobile']); ?>" required>
                    </div>

                    <div>
                        <label for="Department">Department:</label>
                        <input type="text" id="Department" name="Department" value="<?php echo htmlspecialchars($user['Department']); ?>" required>
                    </div>

                    <div>
                        <label for="Work">Work:</label>
                        <input type="text" id="Work" name="Work" value="<?php echo htmlspecialchars($user['Work']); ?>" required>
                    </div>

                    <div>
                        <label for="University">University:</label>
                        <input type="text" id="University" name="University" value="<?php echo htmlspecialchars($user['University']); ?>" required>
                    </div>

                    <div>
                        <label for="profile_picture">Profile Picture (Optional):</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg, image/png">
                    </div>

                    <div>
                        <button type="submit">Update Profile</button>
                    </div>
                </form>
                
            </main>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> UIU Student Hub</p>
    </footer>
</body>
</html>
