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

        body {
            background: #f4f7fb;
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

        .edit-page {
            width: min(800px, 92%);
            margin: 50px auto 80px;
        }

        .edit-card {
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            border: 1px solid #e1e7ee;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
        }

        .edit-card h1 {
            margin: 0 0 8px;
            color: #0b2a4a;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #334155;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font-family: inherit;
            font-size: 15px;
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

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 20px;
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

    </style>

</head>

<body class="admin-body">

    <div class="admin-nav">

        <div class="container">

            <b>
                SPICA ADMIN DEMO
            </b>

            <a href="logout.php">
                Logout
            </a>

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

</body>

</html>