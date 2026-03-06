<?php
require 'db.php';
include 'header.php';

// Fetch Categories
$cats_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cats_stmt->fetchAll();

// Handle Search and Filter
$where = "WHERE 1=1";
$params = [];
if (!empty($_GET['search'])) {
    $where .= " AND (title LIKE ? OR content LIKE ?)";
    $params[] = "%" . $_GET['search'] . "%";
    $params[] = "%" . $_GET['search'] . "%";
}
if (!empty($_GET['cat'])) {
    $where .= " AND category_id = ?";
    $params[] = $_GET['cat'];
}

// Fetch Posts
$sql = "SELECT posts.*, users.username, categories.name as cat_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        LEFT JOIN categories ON posts.category_id = categories.id 
        $where 
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<div style="text-align: center; margin: 4rem 0;">
    <h1 class="neon-text" style="font-size: 3rem; margin-bottom: 1rem;">Welcome to the Future</h1>
    <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 600px; margin: 0 auto;">
        Explore the latest in technology, lifestyle, and design on a platform built for the next generation.
    </p>
</div>

<!-- Search & Filter -->
<div class="glass-card" style="margin-bottom: 3rem; padding: 1.5rem;">
    <form action="index.php" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <input type="text" name="search" placeholder="Search articles..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="flex: 1; margin:0;">
        <select name="cat" style="width: auto; margin:0;">
            <option value="">All Categories</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($_GET['cat']) && $_GET['cat'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<!-- Posts Grid -->
<div class="grid">
    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="glass-card anim-fade-up">
                <?php if($post['image_url']): ?>
                    <div style="height: 200px; background-image: url('<?php echo htmlspecialchars($post['image_url']); ?>'); background-size: cover; background-position: center; border-radius: 8px; margin-bottom: 1rem;"></div>
                <?php else: ?>
                    <div style="height: 200px; background: linear-gradient(45deg, #111, #222); border-radius: 8px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #333;">No Image</div>
                <?php endif; ?>
                
                <span style="color: var(--primary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                    <?php echo htmlspecialchars($post['cat_name'] ?? 'Uncategorized'); ?>
                </span>
                
                <h3 style="margin: 0.5rem 0; font-size: 1.5rem;">
                    <a href="post.php?id=<?php echo $post['id']; ?>" class="hover-glow">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </a>
                </h3>
                
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; line-height: 1.6;">
                    <?php echo substr(htmlspecialchars(strip_tags($post['excerpt'])), 0, 100); ?>...
                </p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                    <small style="color: #666;">By <?php echo htmlspecialchars($post['username']); ?></small>
                    <a href="post.php?id=<?php echo $post['id']; ?>" style="color: var(--accent); font-weight: 600;">Read More &rarr;</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center; width: 100%; grid-column: 1 / -1; color: var(--text-muted);">No posts found. Be the first to write one!</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
