<?php
session_start();
require_once 'db.php';

// Check if user is logged in and if the post ID is set
if (!isset($_SESSION['user_id']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: marketplace.php");
    exit;
}

$postID = $_GET['id'];
$userID = $_SESSION['user_id'];

// Fetch the post from the database
$query = "SELECT * FROM marketplace WHERE ItemID = :postID AND SellerID = :userID";
$stmt = $conn->prepare($query);
$stmt->execute([':postID' => $postID, ':userID' => $userID]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the post exists and belongs to the logged-in user
if (!$post) {
    header("Location: marketplace.php");
    exit;
}

// Delete the post
$query = "DELETE FROM marketplace WHERE ItemID = :postID AND SellerID = :userID";
$stmt = $conn->prepare($query);
$stmt->execute([':postID' => $postID, ':userID' => $userID]);

// Redirect to the marketplace page with a success message
header("Location: marketplace.php?deleted=true");
exit;
