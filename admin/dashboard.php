<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$newsCount = $pdo->query(
    "SELECT COUNT(*) FROM news"
)->fetchColumn();

$msgCount = $pdo->query(
    "SELECT COUNT(*) FROM messages"
)->fetchColumn();

$servicesCount = $pdo->query(
    "SELECT COUNT(*) FROM services"
)->fetchColumn();

$messages = $pdo->query(
    "SELECT * FROM messages ORDER BY created_at DESC LIMIT 8"
)->fetchAll();

?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Dashboard Admin — Spica Indonesia</title>

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
        }

        html[data-theme="dark"] {
            --admin-bg: #101923;
            --admin-card: #182532;
            --admin-text: #f1f5f9;
            --admin-muted: #9aa9ba;
            --admin-border: #2b3a49;
            --admin-hover: #202f3d;
            --admin-primary: #55a9ff;
        }

        .admin-body {
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
            color: #fff;
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

        .admin-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 55px 25px 80px;
        }

        .admin-wrap h1 {
            margin: 0 0 8px;
            font-size: 38px;
            color: var(--admin-text) !important;
        }

        .admin-subtitle {
            margin: 0 0 35px;
            color: var(--admin-muted) !important;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 45px;
        }

        .admin-stat {
            background: var(--admin-card) !important;
            border: 1px solid var(--admin-border) !important;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(7, 26, 45, .06);
            transition: .25s ease;
        }

        .admin-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(7, 26, 45, .10);
        }

        .admin-stat-icon {
            font-size: 25px;
            margin-bottom: 15px;
        }

        .admin-stat-number {
            display: block;
            font-size: 32px;
            font-weight: 800;
            color: var(--admin-primary) !important;
            margin-bottom: 5px;
        }

        .admin-stat-label {
            color: var(--admin-muted) !important;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-section {
            margin-top: 35px;
        }

        .admin-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .admin-section-header h2 {
            margin: 0;
            font-size: 24px;
            color: var(--admin-text) !important;
        }

        .admin-section-header span {
            font-size: 13px;
            color: var(--admin-muted) !important;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 14px;

            padding: 20px;

            background: var(--admin-card) !important;
            border: 1px solid var(--admin-border) !important;
            border-radius: 14px;

            text-decoration: none;
            color: var(--admin-text) !important;

            box-shadow: 0 7px 22px rgba(7, 26, 45, .05);

            transition: .25s ease;
        }

        .quick-action:hover {
            transform: translateY(-3px);
            border-color: var(--admin-primary) !important;
            box-shadow: 0 12px 28px rgba(7, 26, 45, .09);
        }

        .quick-icon {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(11, 94, 215, .10);
            border-radius: 10px;

            font-size: 20px;
            flex-shrink: 0;
        }

        .quick-action strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
            color: var(--admin-text) !important;
        }

        .quick-action small {
            color: var(--admin-muted) !important;
            font-size: 12px;
        }

        .messages-box {
            background: var(--admin-card) !important;
            border: 1px solid var(--admin-border) !important;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(7, 26, 45, .06);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--admin-card) !important;
        }

        .admin-table th {
            background: #071a2d !important;
            color: #ffffff !important;
            text-align: left;
            padding: 16px;
            font-size: 13px;
        }

        .admin-table td {
            padding: 17px 16px;

            border-bottom: 1px solid var(--admin-border) !important;

            font-size: 13px;

            color: var(--admin-text) !important;

            background: var(--admin-card) !important;
        }

        .admin-table tr {
            background: var(--admin-card) !important;
        }

        .admin-table tr:last-child td {
            border-bottom: none !important;
        }

        .admin-table tbody tr:hover td {
            background: var(--admin-hover) !important;
        }

        .message-name {
            font-weight: 800;
            color: var(--admin-text) !important;
        }

        .message-email {
            color: var(--admin-primary) !important;
        }

        .message-subject {
            font-weight: 600;
            color: var(--admin-text) !important;
        }

        .empty-message {
            padding: 40px;
            text-align: center;
            color: var(--admin-muted) !important;
            background: var(--admin-card) !important;
        }

        html[data-theme="dark"] .admin-body {
            background: #101923 !important;
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .admin-stat {
            background: #182532 !important;
            border-color: #2b3a49 !important;
        }

        html[data-theme="dark"] .quick-action {
            background: #182532 !important;
            border-color: #2b3a49 !important;
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .quick-action strong {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .messages-box {
            background: #182532 !important;
            border-color: #2b3a49 !important;
        }

        html[data-theme="dark"] .admin-table {
            background: #182532 !important;
        }

        html[data-theme="dark"] .admin-table tbody tr {
            background: #182532 !important;
        }

        html[data-theme="dark"] .admin-table td {
            background: #182532 !important;
            color: #f1f5f9 !important;
            border-bottom-color: #2b3a49 !important;
        }

        html[data-theme="dark"] .admin-table tbody tr:hover td {
            background: #202f3d !important;
        }

        html[data-theme="dark"] .message-name,
        html[data-theme="dark"] .message-subject {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .message-email {
            color: #55a9ff !important;
        }

        html[data-theme="dark"] .empty-message {
            background: #182532 !important;
            color: #9aa9ba !important;
        }

        @media (max-width: 900px) {

            .admin-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 600px) {

            .admin-wrap {
                padding: 35px 16px 60px;
            }

            .admin-wrap h1 {
                font-size: 30px;
            }

            .admin-stats {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .messages-box {
                overflow-x: auto;
            }

            .admin-table {
                min-width: 700px;
            }

            .admin-nav .container {
                padding-left: 16px;
                padding-right: 16px;
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
                style="color:#fff"
            >
                Logout
            </a>

        </div>

    </div>

</div>


<main class="admin-wrap">


    <h1>
        Dashboard
    </h1>


    <p class="admin-subtitle">
        Welcome back. Manage your Spica Indonesia website from here.
    </p>


    <div class="admin-stats">


        <div class="admin-stat">

            <div class="admin-stat-icon">
                📰
            </div>

            <span class="admin-stat-number">
                <?= $newsCount ?>
            </span>

            <span class="admin-stat-label">
                Total Articles
            </span>

        </div>


        <div class="admin-stat">

            <div class="admin-stat-icon">
                ⚓
            </div>

            <span class="admin-stat-number">
                <?= $servicesCount ?>
            </span>

            <span class="admin-stat-label">
                Total Services
            </span>

        </div>


        <div class="admin-stat">

            <div class="admin-stat-icon">
                💬
            </div>

            <span class="admin-stat-number">
                <?= $msgCount ?>
            </span>

            <span class="admin-stat-label">
                Total Messages
            </span>

        </div>


        <div class="admin-stat">

            <div class="admin-stat-icon">
                📅
            </div>

            <span class="admin-stat-number">
                2026
            </span>

            <span class="admin-stat-label">
                Project Year
            </span>

        </div>


    </div>


    <section class="admin-section">


        <div class="admin-section-header">

            <h2>
                Quick Actions
            </h2>

            <span>
                Manage your website
            </span>

        </div>


        <div class="quick-actions">


            <a
                class="quick-action"
                href="manage_news.php"
            >

                <div class="quick-icon">
                    📰
                </div>

                <div>

                    <strong>
                        Manage News
                    </strong>

                    <small>
                        Add & edit articles
                    </small>

                </div>

            </a>


            <a
                class="quick-action"
                href="manage_services.php"
            >

                <div class="quick-icon">
                    ⚓
                </div>

                <div>

                    <strong>
                        Manage Services
                    </strong>

                    <small>
                        Manage company services
                    </small>

                </div>

            </a>


            <a
                class="quick-action"
                href="manage_messages.php"
            >

                <div class="quick-icon">
                    💬
                </div>

                <div>

                    <strong>
                        Manage Messages
                    </strong>

                    <small>
                        View contact messages
                    </small>

                </div>

            </a>


            <a
                class="quick-action"
                href="../index.php"
            >

                <div class="quick-icon">
                    🌐
                </div>

                <div>

                    <strong>
                        View Website
                    </strong>

                    <small>
                        Open public website
                    </small>

                </div>

            </a>


        </div>

    </section>


    <section class="admin-section">


        <div class="admin-section-header">

            <h2>
                Recent Messages
            </h2>

            <span>
                Latest <?= count($messages) ?> messages
            </span>

        </div>


        <div class="messages-box">


            <?php if (!empty($messages)): ?>


                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Subject
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php foreach ($messages as $m): ?>

                        <tr>

                            <td>

                                <span class="message-name">

                                    <?= htmlspecialchars(
                                        $m["name"]
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <span class="message-email">

                                    <?= htmlspecialchars(
                                        $m["email"]
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <span class="message-subject">

                                    <?= htmlspecialchars(
                                        $m["subject"]
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <?= date(
                                    "d M Y, H:i",
                                    strtotime($m["created_at"])
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>


                    </tbody>

                </table>


            <?php else: ?>


                <div class="empty-message">

                    💬

                    <br><br>

                    There are no messages yet.

                </div>


            <?php endif; ?>


        </div>

    </section>


</main>


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