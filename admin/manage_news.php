<?php

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

        body {
            background: #f4f7fb;
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
            color: #0b2a4a;
        }

        .admin-header p {
            color: #64748b;
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
        }

        .dashboard-btn {
            background: #eaf2ff;
            color: #0b5ed7;
        }

        .add-news-btn {
            background: #0b5ed7;
            color: #ffffff;
        }

        .dashboard-btn:hover {
            background: #dbeafe;
        }

        .add-news-btn:hover {
            background: #084db2;
        }

        .card {
            background: #fff;
            border: 1px solid #e1e8f0;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .news-table {
            width: 100%;
            border-collapse: collapse;
        }

        .news-table th {
            background: #0b2a4a;
            color: white;
            text-align: left;
            padding: 14px;
        }

        .news-table td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .news-table tr:hover {
            background: #f8fafc;
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
            background: #eaf2ff;
            color: #0b5ed7;
        }

        .delete-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        .edit-btn:hover {
            background: #dbeafe;
        }

        .delete-btn:hover {
            background: #fecaca;
        }

        .count {
            color: #64748b;
            font-size: 14px;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #64748b;
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

        <a
            href="logout.php"
            style="color:#fff;"
        >
            Logout
        </a>

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

        <h2 style="color:#0b2a4a;">
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

                                    <strong>
                                        <?= htmlspecialchars(
                                            $item["title"]
                                        ) ?>
                                    </strong>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $item["excerpt"]
                                    ) ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $item["published_at"]
                                    ) ?>

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

</body>

</html>