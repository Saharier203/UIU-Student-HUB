<?php
session_start();
include 'db.php';

if (isset($_POST['post_id']) && isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($comment === '') {
        echo json_encode(['success' => false, 'message' => 'Comment cannot be empty']);
        exit;
    }

    $query = "INSERT INTO comments (UserID, PostID, CommentText) VALUES (:userID, :postID, :commentText)";
    $stmt = $conn->prepare($query);
    $stmt->execute([':userID' => $user_id, ':postID' => $post_id, ':commentText' => $comment]);

    // Fetch user details
    $query = "SELECT FullName, ProfilePicture FROM users WHERE UserID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->execute([':userID' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'userID' => $user_id,
        'fullName' => $user['FullName'],
        'profilePicture' => $user['ProfilePicture'],
        'commentText' => htmlspecialchars($comment)
    ]);
}
?>