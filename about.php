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

$valuesStmt = $pdo->query("
    SELECT *
    FROM about_values
    ORDER BY sort_order ASC, id ASC
");

$values = $valuesStmt->fetchAll();

$strengthsStmt = $pdo->query("
    SELECT *
    FROM about_strengths
    ORDER BY sort_order ASC, id ASC
");

$strengths = $strengthsStmt->fetchAll();

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

        .about-section {
            margin-top: 80px;
        }

        .about-section-head {
            max-width: 720px;
            margin-bottom: 30px;
        }

        .about-section-head h2 {
            margin: 8px 0 12px;
            color: #0b2a4a;
            font-size: 32px;
        }

        .about-section-head p {
            color: #64748b;
            line-height: 1.7;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .value-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 24px;
            transition: 0.25s ease;
        }

        .value-card:hover {
            transform: translateY(-4px);
            border-color: #20a4f3;
        }

        .value-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-bottom: 16px;
            border-radius: 12px;
            background: rgba(32, 164, 243, 0.12);
            color: #0b5ed7;
            font-size: 23px;
        }

        .value-card h3 {
            margin: 0 0 10px;
            color: #0b2a4a;
            font-size: 18px;
        }

        .value-card p {
            margin: 0;
            color: #64748b;
            line-height: 1.7;
            font-size: 14px;
        }

        .strengths-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .strength-card {
            display: flex;
            align-items: flex-start;
            gap: 18px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 24px;
            transition: 0.25s ease;
        }

        .strength-card:hover {
            transform: translateY(-4px);
            border-color: #20a4f3;
        }

        .strength-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(32, 164, 243, 0.12);
            color: #0b5ed7;
            font-size: 23px;
        }

        .strength-card h3 {
            margin: 0 0 10px;
            color: #0b2a4a;
            font-size: 18px;
        }

        .strength-card p {
            margin: 0;
            color: #64748b;
            line-height: 1.7;
            font-size: 14px;
        }

        .about-cta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            margin-top: 80px;
            padding: 35px;
            border-radius: 16px;
            background: #0b5ed7;
        }

        .about-cta h2 {
            margin: 0 0 10px;
            color: #fff;
            font-size: 28px;
        }

        .about-cta p {
            margin: 0;
            color: #e0efff;
            line-height: 1.7;
        }

        .about-cta a {
            display: inline-block;
            padding: 13px 22px;
            border-radius: 8px;
            background: #fff;
            color: #0b5ed7;
            text-decoration: none;
            font-weight: 700;
            white-space: nowrap;
        }

        .about-cta a:hover {
            background: #eaf4ff;
        }

        .about-animate {
            opacity: 0;
            transform: translateY(45px);
            transition:
                opacity 0.8s ease,
                transform 0.8s ease;
        }

        .about-animate.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 1000px) {

            .values-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .company-details {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .company-grid,
            .company-details,
            .values-grid,
            .strengths-grid {
                grid-template-columns: 1fr;
            }

            .about-section-head h2 {
                font-size: 28px;
            }

            .about-cta {
                flex-direction: column;
                align-items: flex-start;
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

<header class="page-hero about-animate">

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

        <div class="eyebrow about-animate">
            COMPANY OVERVIEW
        </div>

        <h2 class="about-animate">
            <?= htmlspecialchars($company["company_name"]) ?>
        </h2>

        <div class="company-overview">

            <p class="company-description about-animate">

                <?= nl2br(
                    htmlspecialchars($company["description"])
                ) ?>

            </p>

            <div class="company-grid">

                <div class="company-card about-animate">

                    <h3>
                        Our Vision
                    </h3>

                    <p>

                        <?= nl2br(
                            htmlspecialchars($company["vision"])
                        ) ?>

                    </p>

                </div>

                <div class="company-card about-animate">

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

                <div class="detail-box about-animate">

                    <strong>
                        Founded
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["founded"]) ?>
                    </span>

                </div>

                <div class="detail-box about-animate">

                    <strong>
                        Location
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["location"]) ?>
                    </span>

                </div>

                <div class="detail-box about-animate">

                    <strong>
                        Email
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["email"]) ?>
                    </span>

                </div>

                <div class="detail-box about-animate">

                    <strong>
                        Phone
                    </strong>

                    <span>
                        <?= htmlspecialchars($company["phone"]) ?>
                    </span>

                </div>

            </div>

        </div>

        <div class="about-section">

            <div class="about-section-head about-animate">

                <div class="eyebrow">
                    OUR CORE VALUES
                </div>

                <h2>
                    What We Stand For
                </h2>

                <p>
                    Our values guide the way we support clients,
                    build partnerships, and improve our maritime services.
                </p>

            </div>

            <div class="values-grid">

                <?php foreach ($values as $value): ?>

                    <div class="value-card about-animate">

                        <div class="value-icon">

                            <?= htmlspecialchars(
                                $value["icon"]
                            ) ?>

                        </div>

                        <h3>

                            <?= htmlspecialchars(
                                $value["title"]
                            ) ?>

                        </h3>

                        <p>

                            <?= htmlspecialchars(
                                $value["description"]
                            ) ?>

                        </p>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

        <div class="about-section">

            <div class="about-section-head about-animate">

                <div class="eyebrow">
                    WHY CHOOSE US
                </div>

                <h2>
                    Support You Can Rely On
                </h2>

                <p>
                    We aim to provide practical and responsive
                    maritime support through professional service
                    and regional cooperation.
                </p>

            </div>

            <div class="strengths-grid">

                <?php foreach ($strengths as $strength): ?>

                    <div class="strength-card about-animate">

                        <div class="strength-icon">

                            <?= htmlspecialchars(
                                $strength["icon"]
                            ) ?>

                        </div>

                        <div>

                            <h3>

                                <?= htmlspecialchars(
                                    $strength["title"]
                                ) ?>

                            </h3>

                            <p>

                                <?= htmlspecialchars(
                                    $strength["description"]
                                ) ?>

                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

        <div class="about-cta about-animate">

            <div>

                <h2>
                    Need Maritime Support?
                </h2>

                <p>
                    Explore our services or contact us to learn
                    more about our maritime support solutions.
                </p>

            </div>

            <a href="contact.php">
                Contact Us →
            </a>

        </div>

    </div>

</section>

<footer class="footer about-animate">

    <div class="container">

        <p>
            ©️ 2026 Dummy Spica Indonesia Project
        </p>

    </div>

</footer>

<script src="assets/js/app.js"></script>

<script>

    const aboutAnimatedElements = document.querySelectorAll(".about-animate");

    const aboutObserver = new IntersectionObserver(
        (entries) => {

            entries.forEach((entry) => {

                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                    aboutObserver.unobserve(entry.target);
                }

            });

        },
        {
            threshold: 0.12
        }
    );

    aboutAnimatedElements.forEach((element) => {
        aboutObserver.observe(element);
    });

</script>

</body>

</html>