<?php
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
             echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
             exit;
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
include 'header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-card anim-fade-up" style="width: 100%; max-width: 400px; padding: 3rem;">
        <h2 class="neon-text" style="text-align: center; margin-bottom: 2rem;">Join the Future</h2>
        
        <?php if($error): ?>
            <div style="color: #ff4d4d; margin-bottom: 1rem; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Sign Up</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted);">
            Already have an account? <a href="login.php" style="color: var(--primary);">Login here</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
