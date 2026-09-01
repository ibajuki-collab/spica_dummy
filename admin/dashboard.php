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

        .admin-body {
            background: #f4f7fb;
            color: #0b2a4a;
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


        .admin-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 55px 25px 80px;
        }

        .admin-wrap h1 {
            margin: 0 0 8px;
            font-size: 38px;
            color: #0b2a4a;
        }

        .admin-subtitle {
            margin: 0 0 35px;
            color: #64748b;
        }


        .admin-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 45px;
        }

        .admin-stat {
            background: #ffffff;
            border: 1px solid #e1e8f0;
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
            color: #0b5ed7;
            margin-bottom: 5px;
        }

        .admin-stat-label {
            color: #64748b;
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
            color: #0b2a4a;
        }

        .admin-section-header span {
            font-size: 13px;
            color: #64748b;
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
            background: #ffffff;
            border: 1px solid #e1e8f0;
            border-radius: 14px;
            text-decoration: none;
            color: #0b2a4a;
            box-shadow: 0 7px 22px rgba(7, 26, 45, .05);
            transition: .25s ease;
        }

        .quick-action:hover {
            transform: translateY(-3px);
            border-color: #0b5ed7;
            box-shadow: 0 12px 28px rgba(7, 26, 45, .09);
        }

        .quick-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef5ff;
            border-radius: 10px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .quick-action strong {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .quick-action small {
            color: #64748b;
            font-size: 12px;
        }


        .messages-box {
            background: #ffffff;
            border: 1px solid #e1e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(7, 26, 45, .06);
        }


        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            background: #071a2d;
            color: #ffffff;
            text-align: left;
            padding: 16px;
            font-size: 13px;
        }

        .admin-table td {
            padding: 17px 16px;
            border-bottom: 1px solid #edf1f5;
            font-size: 13px;
            color: #334155;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tbody tr:hover {
            background: #f8fafc;
        }

        .message-name {
            font-weight: 800;
            color: #0b2a4a;
        }

        .message-email {
            color: #0b5ed7;
        }

        .message-subject {
            font-weight: 600;
        }


        .view-all {
            display: inline-block;
            margin-top: 18px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            color: #0b5ed7;
        }



        .empty-message {
            padding: 40px;
            text-align: center;
            color: #64748b;
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

        <a
            href="logout.php"
            style="color:#fff"
        >
            Logout
        </a>

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


        <?php if (!empty($messages)): ?>

            <a
                href="manage_messages.php"
                class="view-all"
            >
                View all messages →
            </a>

        <?php endif; ?>


    </section>


</main>


</body>

</html>