<?php require "config/database.php"; ?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Services — Spica Indonesia</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    
<body>
    <nav class="navbar">
        <div class="container nav-inner">   <a class="logo" href="index.php">SPICA
            <span>INDONESIA</span>
        </a>
        <button class="menu">☰</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a class="active" href="services.php">Services</a>
            <a href="network.php">Network</a>
            <a href="news.php">Updates</a>
            <a href="contact.php">Contact</a>
        </div>
    </div>
</nav>
<header class="page-hero">
    <div class="container">
        <h1>Our Services</h1>
        <p>Sample service catalog loaded from MySQL.</p>
    </div>
</header>
<section class="section">
    <div class="container">
        <div class="cards"><?php foreach($pdo->query("SELECT * FROM services ORDER BY id") as $s): ?>
            <div class="card">
                <div class="icon">
                    <?=htmlspecialchars($s["icon"])?>
                </div>
                <h3><?=htmlspecialchars($s["name"])?></h3>
                <p><?=htmlspecialchars($s["description"])?></p>
            </div><?php endforeach; ?>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <p>© 2026 Dummy Spica Indonesia Project</p>
    </div>
</footer>
        <script src="assets/js/app.js">
    </script>
</body>
</html>