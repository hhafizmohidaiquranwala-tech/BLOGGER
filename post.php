<?php
require 'db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$post_id = $_GET['id'];

// Handle Comment Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please login to comment.'); window.location.href='login.php';</script>";
        exit;
    }
    
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $_SESSION['user_id'], $comment]);
        // Refresh to show new comment
        echo "<script>window.location.href='post.php?id=" . $post_id . "';</script>";
        exit;
    }
}

// Fetch Post
$stmt = $pdo->prepare("SELECT posts.*, users.username, categories.name as cat_name 
                       FROM posts 
                       JOIN users ON posts.user_id = users.id 
                       LEFT JOIN categories ON posts.category_id = categories.id 
                       WHERE posts.id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<script>alert('Post not found.'); window.location.href='index.php';</script>";
    exit;
}

// Fetch Comments
$c_stmt = $pdo->prepare("SELECT comments.*, users.username 
                         FROM comments 
                         JOIN users ON comments.user_id = users.id 
                         WHERE post_id = ? 
                         ORDER BY created_at DESC");
$c_stmt->execute([$post_id]);
$comments = $c_stmt->fetchAll();

include 'header.php';
?>

<div class="glass-card anim-fade-up" style="max-width: 900px; margin: 2rem auto; padding: 0; overflow: hidden;">
    <?php if($post['image_url']): ?>
        <div style="height: 400px; background-image: url('<?php echo htmlspecialchars($post['image_url']); ?>'); background-size: cover; background-position: center;"></div>
    <?php endif; ?>
    
    <div style="padding: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <span style="color: var(--primary); text-transform: uppercase; letter-spacing: 2px; font-weight: bold;">
                <?php echo htmlspecialchars($post['cat_name']); ?>
            </span>
            <span style="color: var(--text-muted); font-size: 0.9rem;">
                <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
            </span>
        </div>

        <h1 class="neon-text" style="font-size: 3rem; margin-bottom: 2rem; line-height: 1.2;">
            <?php echo htmlspecialchars($post['title']); ?>
        </h1>

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
            <div style="color: #fff;">
                By <span style="color: var(--secondary); font-weight: bold;"><?php echo htmlspecialchars($post['username']); ?></span>
            </div>
            
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                <div>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.8rem; margin-right: 0.5rem;">Edit</a>
                    <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn" style="background: rgba(255,0,0,0.2); color: #ff4d4d; border: 1px solid #ff4d4d; padding: 0.5rem 1rem; font-size: 0.8rem;" onclick="return confirm('Delete this post?');">Delete</a>
                </div>
            <?php endif; ?>
        </div>

        <div style="font-size: 1.1rem; line-height: 1.8; color: #ddd; margin-bottom: 4rem;">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?> 
            <!-- If you trust the HTML content from previous strict inputs, you could remove htmlspecialchars, but it's safer to keep it or use a purifier library. For this simplified scope, we use nl2br. -->
        </div>

        <!-- Comments Section -->
        <h3 class="neon-text-pink" style="margin-bottom: 2rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem;">
            Comments (<?php echo count($comments); ?>)
        </h3>

        <?php if(isset($_SESSION['user_id'])): ?>
            <form method="POST" action="" style="margin-bottom: 3rem;">
                <textarea name="comment" required rows="3" placeholder="Leave a comment..." style="margin-bottom: 1rem;"></textarea>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        <?php else: ?>
            <div style="margin-bottom: 3rem; color: var(--text-muted);">
                <a href="login.php" style="color: var(--primary);">Login</a> to leave a comment.
            </div>
        <?php endif; ?>

        <div class="comments-list">
            <?php foreach($comments as $comment): ?>
                <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--secondary); font-weight: bold;"><?php echo htmlspecialchars($comment['username']); ?></span>
                        <span style="color: var(--text-muted); font-size: 0.8rem;"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                    </div>
                    <p style="color: #ccc;"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
