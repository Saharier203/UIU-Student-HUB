<?php
// Start the session
session_start();

// Connect to the database
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize the search query and filter for 'my posts'
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Fetch marketplace posts from the database for the logged-in user
$query = "SELECT m.ItemID, m.ItemName, m.Description, m.Price, m.CreatedAt, m.ImagePath, u.UserID, u.FullName, u.ProfilePicture 
          FROM marketplace m 
          INNER JOIN users u ON m.SellerID = u.UserID 
          WHERE m.SellerID = :userID AND m.ItemName LIKE :searchQuery
          ORDER BY m.CreatedAt DESC";

try {
    $stmt = $conn->prepare($query); // Prepare the query
    $params = [':searchQuery' => '%' . $searchQuery . '%', ':userID' => $_SESSION['user_id']];
    $stmt->execute($params); // Execute the query with the search parameter
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
} catch (PDOException $e) {
    die('Error fetching marketplace posts: ' . $e->getMessage());
}

// Handle like and comment actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['like'])) {
        $postID = $_POST['post_id'];
        $userID = $_SESSION['user_id'];

        // Check if the user has already liked the post
        $checkLikeQuery = "SELECT * FROM likes WHERE UserID = :userID AND PostID = :postID";
        $checkLikeStmt = $conn->prepare($checkLikeQuery);
        $checkLikeStmt->execute([':userID' => $userID, ':postID' => $postID]);
        $likeExists = $checkLikeStmt->fetch(PDO::FETCH_ASSOC);

        if ($likeExists) {
            // Remove the like if it exists
            $query = "DELETE FROM likes WHERE UserID = :userID AND PostID = :postID";
            $stmt = $conn->prepare($query);
            $stmt->execute([':userID' => $userID, ':postID' => $postID]);
        } else {
            // Insert the like if it doesn't exist
            $query = "INSERT INTO likes (UserID, PostID) VALUES (:userID, :postID)";
            $stmt = $conn->prepare($query);
            $stmt->execute([':userID' => $userID, ':postID' => $postID]);
        }
    } elseif (isset($_POST['comment'])) {
        $postID = $_POST['post_id'];
        $userID = $_SESSION['user_id'];
        $commentText = $_POST['comment_text'];
        $query = "INSERT INTO comments (UserID, PostID, CommentText) VALUES (:userID, :postID, :commentText)";
        $stmt = $conn->prepare($query);
        $stmt->execute([':userID' => $userID, ':postID' => $postID, ':commentText' => $commentText]);
    }
}

// Fetch likes and comments for each post
$likesQuery = "SELECT PostID, COUNT(*) as LikeCount FROM likes GROUP BY PostID";
$likesStmt = $conn->prepare($likesQuery);
$likesStmt->execute();
$likes = $likesStmt->fetchAll(PDO::FETCH_KEY_PAIR);

$commentsQuery = "SELECT PostID, COUNT(*) as CommentCount FROM comments GROUP BY PostID";
$commentsStmt = $conn->prepare($commentsQuery);
$commentsStmt->execute();
$commentsCount = $commentsStmt->fetchAll(PDO::FETCH_KEY_PAIR);

$commentsQuery = "SELECT c.PostID, c.CommentText, u.UserID, u.FullName, u.ProfilePicture FROM comments c INNER JOIN users u ON c.UserID = u.UserID ORDER BY c.CreatedAt ASC";
$commentsStmt = $conn->prepare($commentsQuery);
$commentsStmt->execute();
$comments = $commentsStmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="css/marketplace.css">
    <style>
        .comment-section, .comment-form {
            display: none;
        }
        .comment {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .comment .profile-picture {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
        }
        .comment .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
    <script>
        function toggleComments(postId) {
            var commentSection = document.getElementById('comments-' + postId);
            if (commentSection.style.display === 'none' || commentSection.style.display === '') {
                commentSection.style.display = 'block';
            } else {
                commentSection.style.display = 'none';
            }
        }

        function toggleCommentForm(postId) {
            var commentForm = document.getElementById('comment-form-' + postId);
            if (commentForm.style.display === 'none' || commentForm.style.display === '') {
                commentForm.style.display = 'block';
            } else {
                commentForm.style.display = 'none';
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h1>My Posts</h1>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="marketplace.php">All Posts</a>
            <a href="my_post.php">My Posts</a>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="my_post.php">
                <input type="text" name="search" placeholder="Search by product name" value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Marketplace Posts Grid -->
        <div class="post-grid">
            <?php if (empty($result)): ?>
                <p>No posts found.</p>
            <?php else: ?>
                <?php foreach ($result as $row): ?>
                    <div class="post-card">
                        <!-- Post Header -->
                        <div class="post-header">
                            <div class="profile-picture">
                                <a href="post_profile.php?user_id=<?= $row['UserID'] ?>">
                                    <img src="<?= htmlspecialchars($row['ProfilePicture']) ?>" alt="Profile Picture">
                                </a>
                            </div>
                            <div class="user-info">
                                <a href="post_profile.php?user_id=<?= $row['UserID'] ?>">
                                    <p class="user-name"><?= htmlspecialchars($row['FullName']) ?></p>
                                </a>
                                <p class="post-time">Posted on <?= date('F j, Y, g:i a', strtotime($row['CreatedAt'])) ?></p>
                            </div>
                        </div>

                        <!-- Post Body -->
                        <div class="post-body">
                            <p><strong><?= htmlspecialchars($row['ItemName']) ?></strong></p>
                            <p><?= htmlspecialchars($row['Description']) ?></p>
                            <img src="user_post/<?= htmlspecialchars($row['ImagePath']) ?>" alt="Item Image">
                        </div>

                        <!-- Post Footer -->
                        <div class="post-footer">
                            <p class="price">Price: $<?= number_format($row['Price'], 2) ?></p>
                            <div class="post-actions">
                                <a href="edit_post.php?id=<?= $row['ItemID'] ?>">Edit</a>
                                <a href="delete_post.php?id=<?= $row['ItemID'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>
                            <div class="like-comment">
                                <form method="POST" action="my_post.php">
                                    <input type="hidden" name="post_id" value="<?= $row['ItemID'] ?>">
                                    <button type="submit" name="like">Like</button>
                                    <span class="like-count"><?= $likes[$row['ItemID']] ?? 0 ?> Likes</span>
                                </form>
                                <button onclick="toggleCommentForm(<?= $row['ItemID'] ?>)">Comment</button>
                            </div>
                            <button onclick="toggleComments(<?= $row['ItemID'] ?>)">See Comments (<?= $commentsCount[$row['ItemID']] ?? 0 ?>)</button>
                        </div>

                        <!-- Comment Form -->
                        <div class="comment-form" id="comment-form-<?= $row['ItemID'] ?>">
                            <form method="POST" action="my_post.php">
                                <input type="hidden" name="post_id" value="<?= $row['ItemID'] ?>">
                                <input type="text" name="comment_text" placeholder="Add a comment" required>
                                <button type="submit" name="comment">Submit</button>
                            </form>
                        </div>

                        <!-- Comments Section -->
                        <div class="comment-section" id="comments-<?= $row['ItemID'] ?>">
                            <?php if (isset($comments[$row['ItemID']])): ?>
                                <?php foreach ($comments[$row['ItemID']] as $comment): ?>
                                    <div class="comment">
                                        <div class="profile-picture">
                                            <a href="post_profile.php?user_id=<?= $comment['UserID'] ?>">
                                                <img src="<?= htmlspecialchars($comment['ProfilePicture']) ?>" alt="Profile Picture">
                                            </a>
                                        </div>
                                        <div>
                                            <a href="post_profile.php?user_id=<?= $comment['UserID'] ?>">
                                                <span class="comment-author"><?= htmlspecialchars($comment['FullName']) ?>:</span>
                                            </a>
                                            <p><?= htmlspecialchars($comment['CommentText']) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
