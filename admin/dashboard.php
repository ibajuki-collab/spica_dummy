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
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

    <div class="admin-nav">

        <div class="container">

            <b>SPICA ADMIN DEMO</b>

            <a href="logout.php" style="color:#fff">
                Logout
            </a>

        </div>

    </div>

    <div class="admin-wrap">

        <h1>Dashboard</h1>

        <div class="stats">

            <div class="stat">
                <b><?= $newsCount ?></b>
                Articles
            </div>

            <div class="stat">
                <b><?= $servicesCount ?></b>
                Services
            </div>

            <div class="stat">
                <b><?= $msgCount ?></b>
                Messages
            </div>

            <div class="stat">
                <b>2026</b>
                Project
            </div>

        </div>

        <section class="section" style="padding:35px 0">

            <h2>Recent Messages</h2>

            <div class="table-wrap">

                <table class="admin-table">

                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Date</th>
                    </tr>

                    <?php foreach ($messages as $m): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($m["name"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m["email"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m["subject"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($m["created_at"]) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        </section>

        <div class="btns">

            <a class="btn btn-primary" href="../index.php">
                View Website
            </a>

        </div>

    </div>

</body>

</html>