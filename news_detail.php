<?php
require "config/database.php";

$id = (int) ($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: news.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM news
    WHERE id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    header("Location: news.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($article["title"]) ?> — Spica Indonesia</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .article-page {
            padding: 70px 0 90px;
        }

        .article-container {
            width: min(850px, 92%);
            margin: auto;
        }

        .back-news {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 30px;
            color: #0b5ed7;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: .2s;
        }

        .back-news:hover {
            transform: translateX(-3px);
        }

        .article-card {
            background: #ffffff;
            border: 1px solid #e1e8f0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(7, 26, 45, .08);
        }

        .article-image {
            width: 100%;
            height: 400px;
            overflow: hidden;
            background: #071a2d;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .article-cover {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #071a2d, #0b5ed7);
            color: #ffffff;
        }

        .article-cover-content {
            text-align: center;
        }

        .article-cover-content span {
            display: block;
            font-size: 14px;
            letter-spacing: 3px;
            font-weight: 700;
            opacity: .75;
            margin-bottom: 8px;
        }

        .article-cover-content strong {
            display: block;
            font-size: 42px;
            letter-spacing: 2px;
        }

        .article-anchor {
            position: absolute;
            right: 35px;
            bottom: 25px;
            font-size: 55px;
            opacity: .15;
        }

        .article-body {
            padding: 45px;
        }

        .article-date {
            color: #0b5ed7;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .article-body h1 {
            margin: 0 0 22px;
            color: #0b2a4a;
            font-size: 38px;
            line-height: 1.25;
        }

        .article-excerpt {
            color: #64748b;
            font-size: 17px;
            line-height: 1.8;
            padding-bottom: 25px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 25px;
        }

        .article-content {
            color: #334155;
            font-size: 16px;
            line-height: 1.9;
        }

        .article-content p {
            margin-bottom: 20px;
        }

        .article-tag {
            display: inline-block;
            margin-top: 30px;
            padding: 8px 13px;
            border-radius: 20px;
            background: #eef5ff;
            color: #0b5ed7;
            font-size: 12px;
            font-weight: 800;
        }

        .article-animate {
            opacity: 0;
            transform: translateY(45px);
            transition:
                opacity 0.8s ease,
                transform 0.8s ease;
        }

        .article-animate.show {
            opacity: 1;
            transform: translateY(0);
        }

        .article-image.article-animate,
        .article-cover.article-animate {
            transition-delay: 0.1s;
        }

        .article-body > .article-date {
            transition-delay: 0.15s;
        }

        .article-body > h1 {
            transition-delay: 0.25s;
        }

        .article-body > .article-excerpt {
            transition-delay: 0.35s;
        }

        .article-body > .article-content {
            transition-delay: 0.45s;
        }

        .article-body > .article-tag {
            transition-delay: 0.55s;
        }

        body.dark .article-card,
        body.dark-mode .article-card {
            background: #111c31;
            border-color: #263958;
            box-shadow: 0 15px 45px rgba(0, 0, 0, .20);
        }

        body.dark .back-news,
        body.dark-mode .back-news {
            color: #60c7ff;
        }

        body.dark .article-body h1,
        body.dark-mode .article-body h1 {
            color: #f8fafc;
        }

        body.dark .article-date,
        body.dark-mode .article-date {
            color: #60c7ff;
        }

        body.dark .article-excerpt,
        body.dark-mode .article-excerpt {
            color: #aebbd0;
            border-color: #263958;
        }

        body.dark .article-content,
        body.dark-mode .article-content {
            color: #c7d3e3;
        }

        body.dark .article-tag,
        body.dark-mode .article-tag {
            background: #132b49;
            color: #60c7ff;
        }

        @media (max-width: 700px) {
            .article-page {
                padding: 45px 0 60px;
            }

            .article-image {
                height: 230px;
            }

            .article-cover {
                height: 220px;
            }

            .article-cover-content strong {
                font-size: 30px;
            }

            .article-anchor {
                right: 20px;
                bottom: 15px;
                font-size: 40px;
            }

            .article-body {
                padding: 28px 22px;
            }

            .article-body h1 {
                font-size: 28px;
            }

            .article-excerpt {
                font-size: 15px;
            }

            .article-content {
                font-size: 15px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .article-animate {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="container nav-inner">

        <a class="logo" href="index.php">
            SPICA<span>INDONESIA</span>
        </a>

        <button class="menu" type="button">
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
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="network.php">Network</a>
            <a class="active" href="news.php">Updates</a>
            <a href="contact.php">Contact</a>
        </div>

    </div>
</nav>

<main class="article-page">

    <div class="article-container">

        <a href="news.php" class="back-news article-animate">
            ← Back to Updates
        </a>

        <article class="article-card">

            <?php if (!empty($article["image"])): ?>

                <div class="article-image article-animate">
                    <img
                        src="uploads/news/<?= htmlspecialchars($article["image"]) ?>"
                        alt="<?= htmlspecialchars($article["title"]) ?>"
                    >
                </div>

            <?php else: ?>

                <div class="article-cover article-animate">

                    <div class="article-cover-content">
                        <span>SPICA INDONESIA</span>
                        <strong>MARITIME</strong>
                    </div>

                    <div class="article-anchor">
                        ⚓
                    </div>

                </div>

            <?php endif; ?>

            <div class="article-body">

                <div class="article-date article-animate">
                    📅 <?= date("d M Y", strtotime($article["published_at"])) ?>
                </div>

                <h1 class="article-animate">
                    <?= htmlspecialchars($article["title"]) ?>
                </h1>

                <div class="article-excerpt article-animate">
                    <?= htmlspecialchars($article["excerpt"]) ?>
                </div>

                <div class="article-content article-animate">

                    <p>
                        <?= nl2br(htmlspecialchars($article["excerpt"])) ?>
                    </p>

                    <p>
                        This article is part of the
                        Spica Indonesia maritime updates
                        demonstration. The content shown
                        here is sample information for
                        the website project.
                    </p>

                    <p>
                        Additional approved company
                        information can be added here
                        when the website is ready for
                        production use.
                    </p>

                </div>

                <span class="article-tag article-animate">
                    MARITIME UPDATE
                </span>

            </div>

        </article>

    </div>

</main>

<footer class="footer">
    <div class="container">
        <p>©️ 2026 Dummy Spica Indonesia Project</p>
    </div>
</footer>

<script src="assets/js/app.js"></script>

<script>
    const articleAnimatedElements = document.querySelectorAll(".article-animate");

    const articleObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                    articleObserver.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12
        }
    );

    articleAnimatedElements.forEach((element) => {
        articleObserver.observe(element);
    });
</script>

</body>
</html>