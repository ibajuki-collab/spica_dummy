<?php
require "config/database.php";

$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $stmt = $pdo->prepare("
        SELECT *
        FROM news
        WHERE title LIKE ?
        OR excerpt LIKE ?
        ORDER BY published_at DESC
    ");

    $keyword = "%" . $search . "%";

    $stmt->execute([
        $keyword,
        $keyword
    ]);

    $news = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("
        SELECT *
        FROM news
        ORDER BY published_at DESC
    ");

    $news = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Updates — Spica Indonesia</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

    <style>
        .news-animate {
            opacity: 0;
            transform: translateY(50px);
            transition:
                opacity 0.8s ease,
                transform 0.8s ease;
        }

        .news-animate.show {
            opacity: 1;
            transform: translateY(0);
        }

        .professional-news-card {
            transition:
                opacity 0.8s ease,
                transform 0.8s ease,
                box-shadow 0.3s ease;
        }

        .news-search.news-animate {
            transition-delay: 0.1s;
        }

        .news-result-info.news-animate {
            transition-delay: 0.2s;
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

            <a href="about.php">
                About
            </a>

            <a href="services.php">
                Services
            </a>

            <a href="network.php">
                Network
            </a>

            <a
                class="active"
                href="news.php"
            >
                Updates
            </a>

            <a href="contact.php">
                Contact
            </a>

        </div>

    </div>
</nav>

<header class="news-hero news-animate">
    <div class="container">

        <h1>
            Maritime Updates
        </h1>

        <p>
            Stay informed with the latest maritime
            news, insights and regional updates.
        </p>

    </div>
</header>

<section class="section news-page">

    <div class="container">

        <div class="news-heading news-animate">

            <div>

                <span class="eyebrow">
                    NEWS & UPDATES
                </span>

                <h2>
                    Latest Articles
                </h2>

                <p>
                    Explore the latest information from
                    Spica Indonesia.
                </p>

            </div>

        </div>

        <form
            method="GET"
            action="news.php"
            class="news-search news-animate"
        >

            <div class="search-input-wrapper">

                <span class="search-icon">
                    🔍
                </span>

                <input
                    type="text"
                    name="search"
                    placeholder="Search maritime updates..."
                    value="<?= htmlspecialchars($search) ?>"
                >

            </div>

            <button
                type="submit"
                class="search-button"
            >
                Search
            </button>

            <?php if ($search !== ""): ?>

                <a
                    href="news.php"
                    class="reset-button"
                >
                    Reset
                </a>

            <?php endif; ?>

        </form>

        <div class="news-result-info news-animate">

            <?php if ($search !== ""): ?>

                <span>
                    Search results for
                    <strong>
                        "<?= htmlspecialchars($search) ?>"
                    </strong>
                </span>

                <span>
                    <?= count($news) ?> article(s)
                </span>

            <?php else: ?>

                <span>
                    Latest maritime information
                </span>

                <span>
                    <?= count($news) ?> article(s)
                </span>

            <?php endif; ?>

        </div>

        <?php if (!empty($news)): ?>

            <div class="professional-news-grid">

                <?php foreach ($news as $index => $n): ?>

                    <article
                        class="professional-news-card news-animate"
                        style="transition-delay: <?= 0.1 + ($index * 0.12) ?>s;"
                    >

                        <div class="professional-news-cover">

                            <?php if (!empty($n["image"])): ?>

                                <img
                                    src="uploads/news/<?= htmlspecialchars($n["image"]) ?>"
                                    alt="<?= htmlspecialchars($n["title"]) ?>"
                                    class="news-cover-image"
                                >

                            <?php else: ?>

                                <div class="cover-overlay">

                                    <span>
                                        SPICA
                                    </span>

                                    <strong>
                                        MARITIME
                                    </strong>

                                </div>

                                <div class="anchor-icon">
                                    ⚓
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="professional-news-body">

                            <div class="professional-news-date">

                                <span class="date-icon">
                                    📅
                                </span>

                                <?= date(
                                    "d M Y",
                                    strtotime($n["published_at"])
                                ) ?>

                            </div>

                            <h3>
                                <?= htmlspecialchars(
                                    $n["title"]
                                ) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars(
                                    $n["excerpt"]
                                ) ?>
                            </p>

                            <div class="news-card-footer">

                                <span>
                                    Maritime Update
                                </span>

                                <a
                                    href="news_detail.php?id=<?= (int) $n['id'] ?>"
                                    class="read-more"
                                >
                                    Read More →
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="news-empty news-animate">

                <div class="empty-icon">
                    🔎
                </div>

                <h3>
                    No articles found
                </h3>

                <p>
                    We couldn't find any news matching
                    your search.
                </p>

                <a
                    href="news.php"
                    class="search-button empty-button"
                >
                    View All Updates
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<footer class="footer news-animate">

    <div class="container">

        <p>
            ©️ 2026 Dummy Spica Indonesia Project
        </p>

    </div>

</footer>

<script src="assets/js/app.js"></script>

<script>
    const newsAnimatedElements = document.querySelectorAll(".news-animate");

    const newsObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("show");
                    newsObserver.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12
        }
    );

    newsAnimatedElements.forEach((element) => {
        newsObserver.observe(element);
    });
</script>

</body>

</html>