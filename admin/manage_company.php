<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$stmt = $pdo->query("
    SELECT *
    FROM company_overview
    ORDER BY id ASC
    LIMIT 1
");

$company = $stmt->fetch();

if (!$company) {
    die("Data company belum tersedia.");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $company_name = trim($_POST["company_name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $vision = trim($_POST["vision"] ?? "");
    $mission = trim($_POST["mission"] ?? "");
    $founded = trim($_POST["founded"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if (
        $company_name === "" ||
        $description === "" ||
        $vision === "" ||
        $mission === ""
    ) {

        $error = "Field utama wajib diisi.";

    } else {

        $update = $pdo->prepare("
            UPDATE company_overview
            SET
                company_name = ?,
                description = ?,
                vision = ?,
                mission = ?,
                founded = ?,
                location = ?,
                email = ?,
                phone = ?
            WHERE id = ?
        ");

        $update->execute([
            $company_name,
            $description,
            $vision,
            $mission,
            $founded,
            $location,
            $email,
            $phone,
            $company["id"]
        ]);

        $stmt = $pdo->prepare("
            SELECT *
            FROM company_overview
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$company["id"]]);

        $company = $stmt->fetch();

        $success = "Company Overview berhasil diperbarui.";
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

    <title>Company Overview — Spica Admin</title>

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

        .company-page {
            width: min(900px, 92%);
            margin: 45px auto 80px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0;
            color: #0b2a4a;
        }

        .page-header p {
            color: #64748b;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            border: 1px solid #e1e7ee;
            box-shadow: 0 8px 25px rgba(0,0,0,.05);
        }

        .form-group {
            margin-bottom: 20px;
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
            padding: 13px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font: inherit;
        }

        .form-group textarea {
            min-height: 140px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #0b5ed7;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
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

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 13px 20px;
            border-radius: 9px;
            background: #e2e8f0;
            color: #334155;
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 700px) {

            .form-grid {
                grid-template-columns: 1fr;
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

            <a href="logout.php">
                Logout
            </a>

        </div>

    </div>

    <div class="company-page">

        <div class="page-header">

            <h1>
                Company Overview
            </h1>

            <p>
                Manage your company's profile information.
            </p>

        </div>

        <?php if ($success): ?>

            <div class="success">
                <?= htmlspecialchars($success) ?>
            </div>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <div class="card">

            <form method="POST">

                <div class="form-group">

                    <label>
                        Company Name
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        value="<?= htmlspecialchars($company["company_name"]) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>
                        Company Description
                    </label>

                    <textarea
                        name="description"
                        required
                    ><?= htmlspecialchars($company["description"]) ?></textarea>

                </div>

                <div class="form-group">

                    <label>
                        Vision
                    </label>

                    <textarea
                        name="vision"
                        required
                    ><?= htmlspecialchars($company["vision"]) ?></textarea>

                </div>

                <div class="form-group">

                    <label>
                        Mission
                    </label>

                    <textarea
                        name="mission"
                        required
                    ><?= htmlspecialchars($company["mission"]) ?></textarea> 

                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label>
                            Founded
                        </label>

                        <input
                            type="text"
                            name="founded"
                            value="<?= htmlspecialchars($company["founded"]) ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label>
                            Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="<?= htmlspecialchars($company["location"]) ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label>
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($company["email"]) ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label>
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="<?= htmlspecialchars($company["phone"]) ?>"
                        >

                    </div>

                </div>

                <div class="actions">

                    <button
                        type="submit"
                        class="save-btn"
                    >
                        💾 Save Changes
                    </button>

                    <a
                        href="dashboard.php"
                        class="back-btn"
                    >
                        ← Dashboard
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>