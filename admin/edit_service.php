<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    die("ID service tidak valid.");
}

$stmt = $pdo->prepare("
    SELECT *
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$service = $stmt->fetch();

if (!$service) {
    die("Service dengan ID $id tidak ditemukan.");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $icon = trim($_POST["icon"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($name === "") {

        $error = "Nama service wajib diisi.";

    } elseif ($icon === "") {

        $error = "Icon service wajib diisi.";

    } elseif ($description === "") {

        $error = "Description wajib diisi.";

    } else {

        $update = $pdo->prepare("
            UPDATE services
            SET
                name = ?,
                icon = ?,
                description = ?
            WHERE id = ?
        ");

        $update->execute([
            $name,
            $icon,
            $description,
            $id
        ]);

        $stmt = $pdo->prepare("
            SELECT *
            FROM services
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$id]);

        $service = $stmt->fetch();

        $success = "Service berhasil diperbarui.";
    }
}

?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Edit Service — Spica Admin</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <style>

        :root {
            --admin-bg: #f4f7fb;
            --admin-card: #ffffff;
            --admin-text: #172033;
            --admin-heading: #0b2a4a;
            --admin-muted: #64748b;
            --admin-border: #dbe3ec;
            --admin-input: #ffffff;
            --admin-shadow: rgba(0, 0, 0, .05);
        }

        html[data-theme="dark"] {
            --admin-bg: #101923;
            --admin-card: #182532;
            --admin-text: #f1f5f9;
            --admin-heading: #f1f5f9;
            --admin-muted: #9aa9ba;
            --admin-border: #2b3a49;
            --admin-input: #111d28;
            --admin-shadow: rgba(0, 0, 0, .25);
        }

        body.admin-body {
            background: var(--admin-bg);
            color: var(--admin-text);
            transition: background .2s ease, color .2s ease;
        }

        .admin-nav {
            background: #061b2e;
            padding: 18px 0;
        }

        .admin-nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-nav b {
            color: white;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
        }

        .admin-theme-button {
            border: 1px solid rgba(255, 255, 255, .25);
            background: rgba(255, 255, 255, .08);
            color: white;
            padding: 8px 13px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: 600;
        }

        .admin-theme-button:hover {
            background: rgba(255, 255, 255, .15);
        }

        .edit-page {
            width: min(800px, 92%);
            margin: 50px auto 80px;
        }

        .edit-card {
            background: var(--admin-card);
            padding: 35px;
            border-radius: 16px;
            border: 1px solid var(--admin-border);
            box-shadow: 0 10px 30px var(--admin-shadow);
        }

        .edit-card h1 {
            margin: 0 0 8px;
            color: var(--admin-heading);
        }

        .subtitle {
            color: var(--admin-muted);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--admin-text);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 14px;
            border: 1px solid var(--admin-border);
            border-radius: 9px;
            font-family: inherit;
            font-size: 15px;
            background: var(--admin-input);
            color: var(--admin-text);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--admin-muted);
        }

        .form-group textarea {
            min-height: 180px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0b5ed7;
        }

        .icon-preview {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        html[data-theme="dark"] .error {
            background: #442125;
            color: #fca5a5;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        html[data-theme="dark"] .success {
            background: #173b29;
            color: #86efac;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .save-btn {
            border: 0;
            background: #0b5ed7;
            color: white;
            padding: 13px 20px;
            border-radius: 9px;
            font-weight: 700;
            cursor: pointer;
        }

        .save-btn:hover {
            background: #084db2;
        }

        .cancel-btn {
            display: inline-flex;
            align-items: center;
            padding: 13px 20px;
            border-radius: 9px;
            background: #e2e8f0;
            color: #334155;
            text-decoration: none;
            font-weight: 700;
        }

        .cancel-btn:hover {
            background: #d6dee8;
        }

        html[data-theme="dark"] .cancel-btn {
            background: #293746;
            color: #e2e8f0;
        }

        html[data-theme="dark"] .cancel-btn:hover {
            background: #344657;
        }

        @media (max-width: 600px) {

            .edit-card {
                padding: 25px;
            }

            .actions {
                flex-direction: column;
            }

            .save-btn,
            .cancel-btn {
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

            <div style="display:flex;align-items:center;gap:15px;">

                <button
                    type="button"
                    class="admin-theme-button"
                >
                    🌙 Dark Mode
                </button>

                <a href="logout.php">
                    Logout
                </a>

            </div>

        </div>

    </div>

    <div class="edit-page">

        <div class="edit-card">

            <h1>
                Edit Service
            </h1>

            <p class="subtitle">
                Editing service #<?= (int)$service["id"] ?>
            </p>

            <?php if ($error): ?>

                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <?php if ($success): ?>

                <div class="success">
                    <?= htmlspecialchars($success) ?>
                </div>

            <?php endif; ?>

            <div class="icon-preview">
                <?= htmlspecialchars($service["icon"]) ?>
            </div>

            <form
                method="POST"
                action="edit_service.php?id=<?= (int)$id ?>"
            >

                <div class="form-group">

                    <label>
                        Service Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        maxlength="100"
                        value="<?= htmlspecialchars($service["name"]) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>
                        Icon
                    </label>

                    <input
                        type="text"
                        name="icon"
                        maxlength="20"
                        value="<?= htmlspecialchars($service["icon"]) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        required
                    ><?= htmlspecialchars($service["description"]) ?></textarea>

                </div>

                <div class="actions">

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        💾 Save Changes
                    </button>

                    <a
                        href="manage_services.php"
                        class="cancel-btn"
                    >
                        ← Back
                    </a>

                </div>

            </form>

        </div>

    </div>

    <script>

        const themeButton = document.querySelector(".admin-theme-button");

        function applyTheme(theme) {

            document.documentElement.setAttribute(
                "data-theme",
                theme
            );

            if (theme === "dark") {

                themeButton.textContent = "☀️ Light Mode";

            } else {

                themeButton.textContent = "🌙 Dark Mode";

            }

        }

        const savedTheme =
            localStorage.getItem("spicaAdminTheme") || "light";

        applyTheme(savedTheme);

        themeButton.addEventListener("click", function () {

            const currentTheme =
                document.documentElement.getAttribute("data-theme");

            const newTheme =
                currentTheme === "dark"
                    ? "light"
                    : "dark";

            localStorage.setItem(
                "spicaAdminTheme",
                newTheme
            );

            applyTheme(newTheme);

        });

    </script>

</body>

</html>