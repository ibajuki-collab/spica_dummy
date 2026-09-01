<?php
require_once __DIR__ . '/config/database.php';

$query = "SELECT * FROM services ORDER BY id ASC";
$result = $pdo->query($query)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Services — Spica Indonesia
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

    <style>
        .services-page {
            padding: 80px 0;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-top: 40px;
        }

        .service-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #e5e7eb;
            transition: 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .service-icon {
            font-size: 38px;
            margin-bottom: 15px;
        }

        .service-card h3 {
            color: #0b2a4a;
            margin-bottom: 12px;
        }

        .service-card p {
            color: #64748b;
            line-height: 1.7;
        }

        body.dark-mode {
            background: #0f172a !important;
            color: #e2e8f0 !important;
        }

        body.dark-mode .navbar {
            background: #020617 !important;
        }

        body.dark-mode .services-page {
            background: #0f172a !important;
        }

        body.dark-mode .services-page h1 {
            color: #f8fafc !important;
        }

        body.dark-mode .services-page > .container > p {
            color: #cbd5e1 !important;
        }

        body.dark-mode .service-card {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: none;
        }

        body.dark-mode .service-card h3 {
            color: #f8fafc !important;
        }

        body.dark-mode .service-card p {
            color: #cbd5e1 !important;
        }

        body.dark-mode .dark-mode-toggle {
            color: #ffffff !important;
        }

        body.dark-mode .footer {
            background: #020617 !important;
        }

        @media (max-width: 900px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container nav-inner">

        <a
            class="logo"
            href="index.php"
        >
            SPICA<span>INDONESIA</span>
        </a>

        <button
            class="menu"
            type="button"
        >
            ☰
        </button>

        <button
            id="darkModeToggle"
            class="dark-mode-toggle"
            type="button"
            aria-label="Toggle dark mode"
        >
            🌙
        </button>

        <div class="nav-links">

            <a href="index.php">
                Home
            </a>

            <a href="about.php">
                About
            </a>

            <a
                class="active"
                href="services.php"
            >
                Services
            </a>

            <a href="network.php">
                Network
            </a>

            <a href="news.php">
                Updates
            </a>

            <a href="contact.php">
                Contact
            </a>

        </div>

    </div>
</nav>

<header class="page-hero">
    <div class="container">

        <h1>
            Our Services
        </h1>

        <p>
            Professional maritime services supporting operations
            across Indonesia and Southeast Asia.
        </p>

    </div>
</header>

<main>

    <section class="services-page">
        <div class="container">

            <div class="services-grid">

                <?php if (!empty($result)): ?>

                    <?php foreach ($result as $service): ?>

                        <div class="service-card">

                            <div class="service-icon">
                                <?= htmlspecialchars(
                                    $service['icon']
                                ) ?>
                            </div>

                            <h3>
                                <?= htmlspecialchars(
                                    $service['name']
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $service['description']
                                ) ?>
                            </p>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>
                        No services available.
                    </p>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>

<footer class="footer">
    <div class="container">

        <p>
            ©️ 2026 Dummy Spica Indonesia Project
        </p>

    </div>
</footer>

<script src="assets/js/app.js"></script>

</body>

</html>