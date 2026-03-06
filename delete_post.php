<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verify ownership
$stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if ($post && $post['user_id'] == $user_id) {
    try {
        $del_stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $del_stmt->execute([$post_id]);
        echo "<script>alert('Post deleted successfully.'); window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error deleting post.'); window.location.href='post.php?id=$post_id';</script>";
    }
} else {
    echo "<script>alert('Unauthorized action.'); window.location.href='index.php';</script>";
}
?>
