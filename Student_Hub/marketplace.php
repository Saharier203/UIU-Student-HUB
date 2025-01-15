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

// Initialize the search query and filter
$searchQuery = '';
$filter = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}
if (isset($_GET['filter']) && $_GET['filter'] === 'my_posts') {
    $filter = 'my_posts';
}

// Fetch marketplace posts from the database
$query = "SELECT m.ItemID, m.ItemName, m.Description, m.Price, m.CreatedAt, m.ImagePath, u.UserID, u.FullName, u.ProfilePicture 
          FROM marketplace m 
          INNER JOIN users u ON m.SellerID = u.UserID 
          WHERE m.ItemName LIKE :searchQuery";

if ($filter === 'my_posts') {
    $query .= " AND m.SellerID = :userID";
}

$query .= " ORDER BY m.CreatedAt DESC";

try {
    $stmt = $conn->prepare($query); // Prepare the query
    $params = [':searchQuery' => '%' . $searchQuery . '%'];
    if ($filter === 'my_posts') {
        $params[':userID'] = $_SESSION['user_id'];
    }
    $stmt->execute($params); // Execute the query with the search parameter
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
} catch (PDOException $e) {
    die('Error fetching marketplace posts: ' . $e->getMessage());
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
    <title>Marketplace</title>
    <link rel="stylesheet" href="css/marketplace.css">
    <style>
        .comment-section, .comment-form, .post-options {
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
        .post-options {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            padding: 5px;
            right: 10px;
            top: 10px;
            z-index: 10;
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

        function togglePostOptions(postId) {
            var optionsMenu = document.getElementById('options-' + postId);
            if (optionsMenu.style.display === 'none' || optionsMenu.style.display === '') {
                optionsMenu.style.display = 'block';
            } else {
                optionsMenu.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    fetch('like_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `post_id=${postId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const likeCount = document.querySelector(`#like-count-${postId}`);
                            likeCount.textContent = `${data.likeCount} Likes`;
                        }
                    });
                });
            });

            document.querySelectorAll('.comment-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    const commentInput = document.querySelector(`#comment-input-${postId}`);
                    const commentText = commentInput.value.trim();
                    if (commentText === '') {
                        alert('Comment cannot be empty');
                        return;
                    }
                    fetch('comment_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `post_id=${postId}&comment=${encodeURIComponent(commentText)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const commentSection = document.querySelector(`#comments-${postId}`);
                            const newComment = document.createElement('div');
                            newComment.classList.add('comment');
                            newComment.innerHTML = `
                                <div class="profile-picture">
                                    <a href="post_profile.php?user_id=${data.userID}">
                                        <img src="${data.profilePicture}" alt="Profile Picture">
                                    </a>
                                </div>
                                <div>
                                    <a href="post_profile.php?user_id=${data.userID}">
                                        <span class="comment-author">${data.fullName}:</span>
                                    </a>
                                    <p>${data.commentText}</p>
                                </div>
                            `;
                            commentSection.appendChild(newComment);
                            commentInput.value = '';
                        }
                    });
                });
            });

            document.querySelectorAll('.comment-btn-toggle').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    toggleCommentForm(postId);
                });
            });
        });
    </script>
</head>
<body>

    <div class="container">
        <h1>Marketplace</h1>

        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="student_dashboard.php">Home</a>
            <a href="marketplace.php">All Posts</a>
            <a href="my_post.php">My Posts</a>
            <a href="add_product.php">Add New Post</a>
           
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="marketplace.php">
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

                            <!-- Three Dot Button for Logged User's Posts -->
                            <?php if ($_SESSION['user_id'] == $row['UserID']): ?>
                                <button class="three-dot-btn" onclick="togglePostOptions(<?= $row['ItemID'] ?>)">...</button>
                                <div class="post-options" id="options-<?= $row['ItemID'] ?>">
                                    <a href="edit_post.php?id=<?= $row['ItemID'] ?>">Edit Post</a><br>
                                    <a href="delete_post.php?id=<?= $row['ItemID'] ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post</a>
                                </div>
                            <?php endif; ?>
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
                            <div class="like-comment">
                                <button class="like-btn" data-post-id="<?= $row['ItemID'] ?>">Like</button>
                                <span id="like-count-<?= $row['ItemID'] ?>" class="like-count"><?= $likes[$row['ItemID']] ?? 0 ?> Likes</span>
                                <button class="comment-btn-toggle" data-post-id="<?= $row['ItemID'] ?>">Comment</button>
                            </div>
                            <button onclick="toggleComments(<?= $row['ItemID'] ?>)">See Comments (<?= $commentsCount[$row['ItemID']] ?? 0 ?>)</button>
                        </div>

                        <!-- Comment Form -->
                        <div class="comment-form" id="comment-form-<?= $row['ItemID'] ?>">
                            <input type="text" id="comment-input-<?= $row['ItemID'] ?>" placeholder="Add a comment" required>
                            <button class="comment-btn" data-post-id="<?= $row['ItemID'] ?>">Submit</button>
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