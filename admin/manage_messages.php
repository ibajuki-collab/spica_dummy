<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

if (isset($_GET["delete"])) {

    $id = (int) $_GET["delete"];

    if ($id > 0) {

        $stmt = $pdo->prepare(
            "DELETE FROM messages WHERE id = ?"
        );

        $stmt->execute([$id]);
    }

    header("Location: manage_messages.php");
    exit;
}

$stmt = $pdo->query("
    SELECT *
    FROM messages
    ORDER BY created_at DESC
");

$messages = $stmt->fetchAll();

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Manage Messages — Spica Admin</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <style>

        .messages-page {
            width: min(1050px, 92%);
            margin: 55px auto 80px;
        }

        .messages-top {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 35px;
        }

        .messages-title {
            margin: 0;
            color: #0b2a4a;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .messages-subtitle {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 15px;
        }

        .back-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 17px;
            border-radius: 10px;
            background: #eaf3ff;
            color: #0b5ed7;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: .2s;
        }

        .back-dashboard:hover {
            transform: translateY(-2px);
            background: #dcecff;
        }

        .messages-summary {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-number {
            min-width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0b5ed7;
            color: white;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 800;
            box-shadow: 0 7px 18px rgba(11, 94, 215, .22);
        }

        .summary-text strong {
            display: block;
            color: #0b2a4a;
            font-size: 15px;
        }

        .summary-text span {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: 13px;
        }

        .message-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .message-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 23px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .06);
            transition: .25s ease;
        }

        .message-card:hover {
            transform: translateY(-3px);
            border-color: #b9d8f7;
            box-shadow: 0 14px 35px rgba(15, 23, 42, .09);
        }

        .message-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sender-avatar {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: linear-gradient(
                135deg,
                #0b2a4a,
                #0b5ed7
            );
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .sender-name {
            margin: 0;
            color: #0b2a4a;
            font-size: 16px;
            font-weight: 800;
        }

        .sender-email {
            display: block;
            margin-top: 4px;
            color: #0b5ed7;
            font-size: 13px;
            text-decoration: none;
        }

        .sender-email:hover {
            text-decoration: underline;
        }

        .message-date {
            color: #94a3b8;
            font-size: 12px;
            white-space: nowrap;
        }

        .message-subject {
            margin: 22px 0 12px;
            color: #0b2a4a;
            font-size: 18px;
            font-weight: 800;
        }

        .message-body {
            background: #f7faff;
            border: 1px solid #e5edf6;
            border-radius: 13px;
            padding: 17px 18px;
            color: #475569;
            font-size: 14px;
            line-height: 1.75;
            white-space: normal;
        }

        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #edf1f5;
        }

        .message-label {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
        }

        .delete-message {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 14px;
            border-radius: 9px;
            background: #fff1f2;
            color: #dc2626;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
        }

        .delete-message:hover {
            background: #fee2e2;
            transform: translateY(-1px);
        }

        .empty-message {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 70px 30px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(15, 23, 42, .05);
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: #eef5fc;
            font-size: 27px;
        }

        .empty-message h3 {
            margin: 0 0 8px;
            color: #0b2a4a;
        }

        .empty-message p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        body.dark-mode .messages-title,
        body.dark .messages-title {
            color: #f8fafc !important;
        }

        body.dark-mode .messages-subtitle,
        body.dark .messages-subtitle {
            color: #9fb0c7 !important;
        }

        body.dark-mode .back-dashboard,
        body.dark .back-dashboard {
            background: #132b49 !important;
            color: #60c7ff !important;
        }

        body.dark-mode .back-dashboard:hover,
        body.dark .back-dashboard:hover {
            background: #193856 !important;
        }

        body.dark-mode .summary-text strong,
        body.dark .summary-text strong {
            color: #f1f5f9 !important;
        }

        body.dark-mode .summary-text span,
        body.dark .summary-text span {
            color: #94a8c1 !important;
        }

        body.dark-mode .message-card,
        body.dark .message-card {
            background: #111c31 !important;
            border-color: #263958 !important;
            box-shadow: 0 10px 30px rgba(0,0,0,.18);
        }

        body.dark-mode .message-card:hover,
        body.dark .message-card:hover {
            border-color: #31557d !important;
            background: #142139 !important;
        }

        body.dark-mode .sender-name,
        body.dark .sender-name {
            color: #f1f5f9 !important;
        }

        body.dark-mode .sender-email,
        body.dark .sender-email {
            color: #60c7ff !important;
        }

        body.dark-mode .message-date,
        body.dark .message-date {
            color: #8092aa !important;
        }

        body.dark-mode .message-subject,
        body.dark .message-subject {
            color: #f1f5f9 !important;
        }

        body.dark-mode .message-body,
        body.dark .message-body {
            background: #17243a !important;
            border-color: #2b405e !important;
            color: #c7d3e3 !important;
        }

        body.dark-mode .message-footer,
        body.dark .message-footer {
            border-color: #263958 !important;
        }

        body.dark-mode .message-label,
        body.dark .message-label {
            color: #8092aa !important;
        }

        body.dark-mode .delete-message,
        body.dark .delete-message {
            background: #3a1d25 !important;
            color: #ff8f9c !important;
        }

        body.dark-mode .delete-message:hover,
        body.dark .delete-message:hover {
            background: #51232d !important;
        }

        body.dark-mode .empty-message,
        body.dark .empty-message {
            background: #111c31 !important;
            border-color: #263958 !important;
        }

        body.dark-mode .empty-message h3,
        body.dark .empty-message h3 {
            color: #f1f5f9 !important;
        }

        body.dark-mode .empty-message p,
        body.dark .empty-message p {
            color: #94a8c1 !important;
        }

        body.dark-mode .empty-icon,
        body.dark .empty-icon {
            background: #172b46 !important;
        }

        @media (max-width: 700px) {

            .messages-page {
                width: 92%;
                margin-top: 35px;
            }

            .messages-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .messages-title {
                font-size: 28px;
            }

            .message-header {
                flex-direction: column;
                gap: 12px;
            }

            .message-date {
                margin-left: 62px;
            }

            .message-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .delete-message {
                width: 100%;
                justify-content: center;
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

<main class="messages-page">

    <div class="messages-top">

        <div>

            <h1 class="messages-title">
                Manage Messages
            </h1>

            <p class="messages-subtitle">
                View and manage messages received from the website contact form.
            </p>

        </div>

        <a
            href="dashboard.php"
            class="back-dashboard"
        >
            ← Dashboard
        </a>

    </div>

    <div class="messages-summary">

        <div class="summary-number">
            <?= count($messages) ?>
        </div>

        <div class="summary-text">

            <strong>
                Total Messages
            </strong>

            <span>
                Messages submitted through Contact Us
            </span>

        </div>

    </div>

    <?php if (!empty($messages)): ?>

        <div class="message-list">

            <?php foreach ($messages as $m): ?>

                <?php

                $name = htmlspecialchars($m["name"]);

                $initial =
                    strtoupper(
                        substr(
                            trim($m["name"]),
                            0,
                            1
                        )
                    );

                ?>

                <article class="message-card">

                    <div class="message-header">

                        <div class="sender-info">

                            <div class="sender-avatar">
                                <?= htmlspecialchars($initial) ?>
                            </div>

                            <div>

                                <h3 class="sender-name">
                                    <?= $name ?>
                                </h3>

                                <a
                                    class="sender-email"
                                    href="mailto:<?= htmlspecialchars($m["email"]) ?>"
                                >
                                    <?= htmlspecialchars($m["email"]) ?>
                                </a>

                            </div>

                        </div>

                        <div class="message-date">

                            <?= date(
                                "d M Y • H:i",
                                strtotime($m["created_at"])
                            ) ?>

                        </div>

                    </div>

                    <h2 class="message-subject">

                        <?= htmlspecialchars(
                            $m["subject"]
                        ) ?>

                    </h2>

                    <div class="message-body">

                        <?= nl2br(
                            htmlspecialchars(
                                $m["message"]
                            )
                        ) ?>

                    </div>

                    <div class="message-footer">

                        <span class="message-label">
                            CONTACT FORM MESSAGE
                        </span>

                        <a
                            class="delete-message"
                            href="manage_messages.php?delete=<?= (int) $m["id"] ?>"
                            onclick="return confirm('Yakin ingin menghapus pesan ini?')"
                        >
                            🗑 Delete
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-message">

            <div class="empty-icon">
                💬
            </div>

            <h3>
                No messages yet
            </h3>

            <p>
                Messages submitted from the Contact page will appear here.
            </p>

        </div>

    <?php endif; ?>

</main>

</body>

</html>