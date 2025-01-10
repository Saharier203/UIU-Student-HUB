<?php
// Include the database connection file
require_once 'db.php'; // Ensure db.php is properly configured

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs
    $fullname = trim($_POST['fullname']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    // Validate inputs
    if (!$email) {
        $error = "Invalid email format.";
    } elseif (empty($fullname) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        try {
            $sql = "INSERT INTO Users (FullName, Email, PasswordHash) VALUES (:fullname, :email, :passwordHash)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':passwordHash', $passwordHash);
            


            if ($stmt->execute()) {
                $success = "Registration successful!";
            } else {
                $error = "Could not execute the query.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="container">
        <div class="welcome-back">
            <h1>Welcome Back!</h1>
            <p>To keep connected with us, please login with your personal info</p>
            <button onclick="window.location.href='login.php'">Sign In</button>
        </div>
        <div class="create-account">
            <h2>Create Account</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
            <form method="POST">
                <input type="text" name="fullname" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

               
                <button type="submit">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
