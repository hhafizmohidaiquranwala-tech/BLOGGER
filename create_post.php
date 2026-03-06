<?php
require 'db.php';
session_start();

// Auth Check
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first'); window.location.href='login.php';</script>";
    exit;
}

$error = '';
$success = '';

// Fetch Categories
$cats_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cats_stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $image_url = trim($_POST['image_url']);
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    // Create excerpt
    $excerpt = substr(strip_tags($content), 0, 150);

    if (empty($title) || empty($content)) {
        $error = "Title and Content are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, excerpt, content, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $title, $excerpt, $content, $category_id, $image_url]);
            echo "<script>alert('Post published successfully!'); window.location.href='index.php';</script>";
            exit;
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
include 'header.php';
?>

<div class="glass-card anim-fade-up" style="max-width: 800px; margin: 4rem auto; padding: 2rem;">
    <h2 class="neon-text" style="text-align: center; margin-bottom: 2rem;">Create New Post</h2>
    
    <?php if($error): ?>
        <div style="color: #ff4d4d; margin-bottom: 1rem; text-align: center;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Title</label>
            <input type="text" name="title" required placeholder="Enter an engaging title">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Category</label>
                <select name="category_id" required>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Image URL</label>
                <input type="text" name="image_url" placeholder="https://">
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Content</label>
            <textarea name="content" rows="10" required placeholder="Write your content here... HTML tags are allowed." style="font-family: monospace;"></textarea>
            <small style="color: var(--text-muted);">You can use basic HTML tags for formatting.</small>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Publish Post</button>
    </form>
</div>

<?php include 'footer.php'; ?>
