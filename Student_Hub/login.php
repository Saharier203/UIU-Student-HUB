<?php
// Include the database connection file
require_once 'db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user by email
    $sql = "SELECT * FROM Users WHERE Email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verify the hashed password
        if (password_verify($password, $row['PasswordHash'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['user_name'] = $row['FullName'];

            // Redirect to the student dashboard
            header("Location: student_dashboard.php");
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</body>
</html>
