<?php
session_start();
include 'db.php';

if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user already liked the post
    $query = "SELECT * FROM likes WHERE UserID = :userID AND PostID = :postID";
    $stmt = $conn->prepare($query);
    $stmt->execute([':userID' => $user_id, ':postID' => $post_id]);
    $likeExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($likeExists) {
        // Remove like
        $query = "DELETE FROM likes WHERE UserID = :userID AND PostID = :postID";
        $stmt = $conn->prepare($query);
        $stmt->execute([':userID' => $user_id, ':postID' => $post_id]);
    } else {
        // Insert like
        $query = "INSERT INTO likes (UserID, PostID) VALUES (:userID, :postID)";
        $stmt = $conn->prepare($query);
        $stmt->execute([':userID' => $user_id, ':postID' => $post_id]);
    }

    // Get updated like count
    $query = "SELECT COUNT(*) as LikeCount FROM likes WHERE PostID = :postID";
    $stmt = $conn->prepare($query);
    $stmt->execute([':postID' => $post_id]);
    $likeCount = $stmt->fetch(PDO::FETCH_ASSOC)['LikeCount'];

    echo json_encode(['success' => true, 'likeCount' => $likeCount]);
}
?>