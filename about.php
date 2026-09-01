<?php

require "config/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM company_overview
    ORDER BY id ASC
    LIMIT 1
");

$company = $stmt->fetch();

if (!$company) {
    die("Data company_overview tidak ditemukan.");
}

?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        About — <?= htmlspecialchars($company["company_name"]) ?>
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

    <style>

        .company-overview {
            margin-top: 30px;
        }

        .company-description {
            font-size: 17px;
            line-height: 1.8;
            color: #64748b;
            max-width: 850px;
        }

        .company-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 40px;
        }

        .company-card {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #e5e7eb;
        }

        .company-card h3 {
            margin-top: 0;
            color: #0b2a4a;
        }

        .company-card p {
            color: #64748b;
            line-height: 1.7;
        }

        .company-details {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 30px;
        }

        .detail-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
        }

        .detail-box strong {
            display: block;
            color: #0b2a4a;
            margin-bottom: 7px;
        }

        .detail-box span {
            color: #64748b;
            line-height: 1.5;
        }

        @media (max-width: 900px) {

            .company-details {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .company-grid,
            .company-details {
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

        <button class="menu">
            ☰
        </button>

        <button
            id="darkModeToggle"
            class="dark-mode-toggle"
            type="button"
        >
            🌙
        </button>

        <div class="nav-links">

            <a href="index.php">
                Home
            </a>

            <a
                class="active"
                href="about.php"
            >
                About
            </a>

            <a href="services.php">
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
            About <?= htmlspecialchars($company["company_name"]) ?>
        </h1>

        <p>
            Company overview and information.
        </p>

    </div>

</header>

<section class="section">

    <div class="container">

        <div class="eyebrow">
            COMPANY OVERVIEW
        </div>

        <h2>
            <?= htmlspecialchars($company["company_name"]) ?>
        </h2>

        <div class="company-overview">

            <p class="company-description">
                <?= nl2br(
                    htmlspecialchars($company["description"])
                ) ?>
            </p>

            <div class="company-grid">

                <div class="company-card">

                    <h3>
                        Our Vision
                    </h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars($company["vision"])
                        ) ?>
                    </p>

                </div>

                <div class="company-card">

                    <h3>
                        Our Mission
                    </h3>

                    <p>
                        <?= nl2br(
                            htmlspecialchars($company["mission"])
                        ) ?>
                    </p>

                </div>

            </div>

            <div class="company-details">

                <div class="detail-box">

                    <strong>
                        Founded
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["founded"]) ?>
                    </span>

                </div>

                <div class="detail-box">

                    <strong>
                        Location
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["location"]) ?>
                    </span>

                </div>

                <div class="detail-box">

                    <strong>
                        Email
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["email"]) ?>
                    </span>

                </div>

                <div class="detail-box">

                    <strong>
                        Phone
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["phone"]) ?>
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>

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