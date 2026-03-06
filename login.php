<?php
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set Session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
include 'header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div class="glass-card anim-fade-up" style="width: 100%; max-width: 400px; padding: 3rem;">
        <h2 class="neon-text-pink" style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>
        
        <?php if($error): ?>
            <div style="color: #ff4d4d; margin-bottom: 1rem; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted);">
            New here? <a href="signup.php" style="color: var(--secondary);">Create an account</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
