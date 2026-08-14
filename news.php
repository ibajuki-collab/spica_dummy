<?php require "config/database.php"; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Updates — Spica Indonesia</title>
        <link rel="stylesheet" href="assets/css/style.css"></head>
        <body>
<nav class="navbar">
    <div class="container nav-inner">
        <a class="logo" href="index.php">SPICA
            <span>INDONESIA</span>
        </a>
        <button class="menu">☰</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="network.php">Network</a>
            <a class="active" href="news.php">Updates</a>
            <a href="contact.php">Contact</a>
        </div>
    </div>
</nav>
<header class="page-hero">
    <div class="container">
        <h1>Maritime Updates</h1>
        <p>Sample articles managed from the admin dashboard.</p>
    </div>
</header>
<section class="section">
    <div class="container"><div class="news-grid">
        <?php foreach($pdo->query("SELECT * FROM news ORDER BY published_at DESC") as $n): ?>
            <article class="news-card">
            <div class="news-cover">⚓</div>
            <div class="news-body"><div class="date">
                <?=htmlspecialchars($n["published_at"])?></div>
                <h3><?=htmlspecialchars($n["title"])?></h3>
                <p><?=htmlspecialchars($n["excerpt"])?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
</section>
    <footer class="footer"><div class="container">
    <p>© 2026 Dummy Spica Indonesia Project</p>
    </div>
</footer>
        <script src="assets/js/app.js">
    </script>
    </body>
</html>