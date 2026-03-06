<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeonBlog - Future of Blogging</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050505;
            --card-bg: rgba(20, 20, 20, 0.6);
            --primary: #00f3ff;
            --secondary: #bc13fe;
            --accent: #ccff00;
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
            --glass-border: 1px solid rgba(255, 255, 255, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(188, 19, 254, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0, 243, 255, 0.1) 0%, transparent 20%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }

        /* Typography */
        h1, h2, h3, h4 { color: var(--text-main); letter-spacing: -0.5px; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }
        
        /* Neon Glow Utility */
        .neon-text {
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.5), 0 0 20px rgba(0, 243, 255, 0.3);
        }
        .neon-text-pink {
            text-shadow: 0 0 10px rgba(188, 19, 254, 0.5), 0 0 20px rgba(188, 19, 254, 0.3);
        }

        /* Glassmorphism Containers */
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 243, 255, 0.15);
            border-color: rgba(0, 243, 255, 0.3);
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }

        .btn-primary {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary);
            color: #000;
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.6);
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--secondary);
            color: var(--secondary);
        }

        .btn-secondary:hover {
            background: var(--secondary);
            color: #fff;
            box-shadow: 0 0 20px rgba(188, 19, 254, 0.6);
        }

        /* Form Elements */
        input, textarea, select {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.2);
        }

        /* Navigation */
        nav {
            padding: 1.5rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(5, 5, 5, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo { font-size: 1.8rem; font-weight: 700; color: var(--text-main); }
        .logo span { color: var(--primary); }

        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a:hover { color: var(--primary); text-shadow: 0 0 8px var(--primary); }

        /* Mobile Menu */
        .mobile-toggle { display: none; font-size: 1.5rem; cursor: pointer; }

        @media (max-width: 768px) {
            .nav-links { display: none; flex-direction: column; position: absolute; top: 100%; left: 0; width: 100%; background: #0a0a0a; padding: 2rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
            .nav-links.active { display: flex; }
            .mobile-toggle { display: block; }
        }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        
        /* Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .anim-fade-up { animation: fadeUp 0.6s ease-out forwards; }
    </style>
    <!-- JS for Redirection Helper -->
    <script>
        function redirect(url) {
            window.location.href = url;
        }
        
        function toggleMenu() {
            document.querySelector('.nav-links').classList.toggle('active');
        }
    </script>
</head>
<body>

<nav>
    <a href="index.php" class="logo">Neon<span>Blog</span></a>
    <div class="mobile-toggle" onclick="toggleMenu()">☰</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="create_post.php">Write Post</a>
            <a href="logout.php" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php" class="btn btn-primary" style="padding: 0.5rem 1rem;">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
