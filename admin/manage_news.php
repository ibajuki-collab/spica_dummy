]<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$stmt = $pdo->query(
    "SELECT * FROM news ORDER BY published_at DESC, id DESC"
);

$news = $stmt->fetchAll();

?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Manage News — Spica Admin</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <style>

        :root {
            --admin-bg: #f4f7fb;
            --admin-card: #ffffff;
            --admin-text: #0b2a4a;
            --admin-muted: #64748b;
            --admin-border: #e1e8f0;
            --admin-hover: #f8fafc;
            --admin-primary: #0b5ed7;
            --admin-soft-blue: #eaf2ff;
            --admin-soft-red: #fee2e2;
            --admin-success-bg: #dcfce7;
            --admin-success-text: #166534;
        }

        html[data-theme="dark"] {
            --admin-bg: #101923;
            --admin-card: #182532;
            --admin-text: #f1f5f9;
            --admin-muted: #9aa9ba;
            --admin-border: #2b3a49;
            --admin-hover: #202f3d;
            --admin-primary: #55a9ff;
            --admin-soft-blue: #173352;
            --admin-soft-red: #42252b;
            --admin-success-bg: #173b2a;
            --admin-success-text: #86efac;
        }

        body.admin-body {
            background: var(--admin-bg) !important;
            color: var(--admin-text) !important;
            transition:
                background .25s ease,
                color .25s ease;
        }

        .admin-nav {
            background: #071a2d;
            color: #ffffff;
            padding: 18px 0;
        }

        .admin-nav .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-nav b {
            font-size: 16px;
            letter-spacing: .3px;
        }

        .admin-nav a {
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .admin-theme-button {
            border: 1px solid rgba(255, 255, 255, .3);
            background: transparent;
            color: #ffffff;
            padding: 7px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: .2s ease;
        }

        .admin-theme-button:hover {
            background: rgba(255, 255, 255, .1);
        }

        .admin-page {
            width: min(1100px, 94%);
            margin: 40px auto 80px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .admin-header h1 {
            margin: 0;
            color: var(--admin-text) !important;
        }

        .admin-header p {
            color: var(--admin-muted) !important;
            margin-top: 8px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .dashboard-btn,
        .add-news-btn {
            text-decoration: none;
            padding: 11px 17px;
            border-radius: 9px;
            font-weight: 700;
            transition: .2s ease;
        }

        .dashboard-btn {
            background: var(--admin-soft-blue);
            color: var(--admin-primary) !important;
        }

        .add-news-btn {
            background: #0b5ed7;
            color: #ffffff !important;
        }

        .dashboard-btn:hover {
            background: var(--admin-hover);
        }

        .add-news-btn:hover {
            background: #084db2;
        }

        .success {
            background: var(--admin-success-bg) !important;
            color: var(--admin-success-text) !important;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .articles-title {
            color: var(--admin-text) !important;
            margin-bottom: 8px;
        }

        .count {
            color: var(--admin-muted) !important;
            font-size: 14px;
            margin-top: 0;
        }

        .card {
            background: var(--admin-card) !important;
            border: 1px solid var(--admin-border) !important;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .04);
            transition:
                background .25s ease,
                border-color .25s ease;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .news-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--admin-card) !important;
        }

        .news-table th {
            background: #0b2a4a !important;
            color: #ffffff !important;
            text-align: left;
            padding: 14px;
        }

        .news-table td {
            padding: 14px;
            border-bottom: 1px solid var(--admin-border) !important;
            vertical-align: top;
            color: var(--admin-text) !important;
            background: var(--admin-card) !important;
        }

        .news-table tbody tr {
            background: var(--admin-card) !important;
        }

        .news-table tbody tr:hover td {
            background: var(--admin-hover) !important;
        }

        .news-table tr:last-child td {
            border-bottom: none !important;
        }

        .news-title {
            color: var(--admin-text) !important;
        }

        .news-description {
            color: var(--admin-text) !important;
            line-height: 1.5;
        }

        .news-date {
            color: var(--admin-muted) !important;
        }

        .action {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            cursor: pointer;
            padding: 9px 14px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

        .edit-btn {
            background: var(--admin-soft-blue);
            color: var(--admin-primary) !important;
        }

        .delete-btn {
            background: var(--admin-soft-red);
            color: #dc2626 !important;
        }

        .edit-btn:hover {
            background: var(--admin-hover);
        }

        .delete-btn:hover {
            background: #fecaca;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: var(--admin-muted) !important;
            background: var(--admin-card) !important;
        }

        html[data-theme="dark"] body.admin-body {
            background: #101923 !important;
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .admin-header h1 {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .admin-header p {
            color: #9aa9ba !important;
        }

        html[data-theme="dark"] .articles-title {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .count {
            color: #9aa9ba !important;
        }

        html[data-theme="dark"] .card {
            background: #182532 !important;
            border-color: #2b3a49 !important;
        }

        html[data-theme="dark"] .news-table {
            background: #182532 !important;
        }

        html[data-theme="dark"] .news-table tbody tr {
            background: #182532 !important;
        }

        html[data-theme="dark"] .news-table td {
            background: #182532 !important;
            color: #f1f5f9 !important;
            border-bottom-color: #2b3a49 !important;
        }

        html[data-theme="dark"] .news-table tbody tr:hover td {
            background: #202f3d !important;
        }

        html[data-theme="dark"] .news-title {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .news-description {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .news-date {
            color: #9aa9ba !important;
        }

        html[data-theme="dark"] .empty {
            background: #182532 !important;
            color: #9aa9ba !important;
        }

        html[data-theme="dark"] .dashboard-btn {
            background: #173352 !important;
            color: #55a9ff !important;
        }

        html[data-theme="dark"] .edit-btn {
            background: #173352 !important;
            color: #55a9ff !important;
        }

        html[data-theme="dark"] .delete-btn {
            background: #42252b !important;
            color: #ff8b8b !important;
        }

        @media (max-width: 700px) {

            .admin-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-actions {
                width: 100%;
            }

            .dashboard-btn,
            .add-news-btn {
                flex: 1;
                text-align: center;
            }

            .news-table th,
            .news-table td {
                padding: 10px;
                font-size: 14px;
            }

        }

    </style>

</head>

<body class="admin-body">

<div class="admin-nav">

    <div class="container">

        <b>
            SPICA ADMIN DEMO
        </b>

        <div
            style="
                display: flex;
                align-items: center;
                gap: 15px;
            "
        >

            <button
                type="button"
                class="admin-theme-button"
            >
                🌙 Dark Mode
            </button>

            <a
                href="logout.php"
                style="color:#fff;"
            >
                Logout
            </a>

        </div>

    </div>

</div>

<div class="admin-page">

    <div class="admin-header">

        <div>

            <h1>
                Manage News
            </h1>

            <p>
                Add, edit and manage website updates.
            </p>

        </div>

        <div class="header-actions">

            <a
                href="dashboard.php"
                class="dashboard-btn"
            >
                ← Dashboard
            </a>

            <a
                href="add_news.php"
                class="add-news-btn"
            >
                + Add New Article
            </a>

        </div>

    </div>

    <?php if (isset($_GET["success"])): ?>

        <div class="success">

            <?php if ($_GET["success"] === "added"): ?>

                News berhasil ditambahkan.

            <?php elseif ($_GET["success"] === "updated"): ?>

                News berhasil diperbarui.

            <?php elseif ($_GET["success"] === "deleted"): ?>

                News berhasil dihapus.

            <?php else: ?>

                Operation berhasil.

            <?php endif; ?>

        </div>

    <?php endif; ?>

    <div>

        <h2 class="articles-title">
            All Articles
        </h2>

        <p class="count">
            <?= count($news) ?> Article(s)
        </p>

    </div>

    <div class="card">

        <div class="table-wrap">

            <table class="news-table">

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Description
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (count($news) > 0): ?>

                        <?php foreach ($news as $item): ?>

                            <tr>

                                <td>
                                    #<?= (int)$item["id"] ?>
                                </td>

                                <td>

                                    <strong class="news-title">

                                        <?= htmlspecialchars(
                                            $item["title"]
                                        ) ?>

                                    </strong>

                                </td>

                                <td>

                                    <span class="news-description">

                                        <?= htmlspecialchars(
                                            $item["excerpt"]
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <span class="news-date">

                                        <?= htmlspecialchars(
                                            $item["published_at"]
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="action">

                                        <a
                                            href="edit_news.php?id=<?= (int)$item["id"] ?>"
                                            class="btn edit-btn"
                                        >
                                            ✏️ Edit
                                        </a>

                                        <a
                                            href="delete_news.php?id=<?= (int)$item["id"] ?>"
                                            class="btn delete-btn"
                                            onclick="return confirm('Yakin ingin menghapus artikel ini?')"
                                        >
                                            🗑️ Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="5"
                                class="empty"
                            >
                                Belum ada artikel.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

    const themeButton =
        document.querySelector(".admin-theme-button");

    function applyTheme(theme) {

        document.documentElement.setAttribute(
            "data-theme",
            theme
        );

        if (theme === "dark") {

            themeButton.textContent =
                "☀️ Light Mode";

        } else {

            themeButton.textContent =
                "🌙 Dark Mode";

        }

    }

    const savedTheme =
        localStorage.getItem("spicaAdminTheme") || "light";

    applyTheme(savedTheme);

    themeButton.addEventListener(
        "click",
        function () {

            const currentTheme =
                document.documentElement.getAttribute(
                    "data-theme"
                );

            const newTheme =
                currentTheme === "dark"
                    ? "light"
                    : "dark";

            localStorage.setItem(
                "spicaAdminTheme",
                newTheme
            );

            applyTheme(newTheme);

        }
    );

</script>

</body>

</html>