<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_service"])) {

    $name = trim($_POST["name"] ?? "");
    $icon = trim($_POST["icon"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($name === "" || $icon === "" || $description === "") {

        $error = "Semua field wajib diisi.";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO services
            (name, icon, description)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $name,
            $icon,
            $description
        ]);

        header("Location: manage_services.php?success=added");
        exit;
    }
}

$stmt = $pdo->query("
    SELECT *
    FROM services
    ORDER BY id ASC
");

$services = $stmt->fetchAll();

?>

<!doctype html>

<html lang="id">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>Manage Services — Spica Admin</title>

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

.dashboard-btn {
    text-decoration: none;
    background: #eaf2ff;
    color: #0b5ed7;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 700;
}

.card {
    background: #fff;
    border: 1px solid #e1e8f0;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,.04);
}

.card h2 {
    margin-top: 0;
    color: #0b2a4a;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 700;
    color: #334155;
}

.form-group input,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 13px;
    border: 1px solid #cbd5e1;
    border-radius: 9px;
    font: inherit;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #0b5ed7;
}

.btn {
    border: none;
    cursor: pointer;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: #0b5ed7;
    color: white;
}

.btn-primary:hover {
    background: #084db2;
}

.success {
    background: #dcfce7;
    color: #166534;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.error {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.table-wrap {
    overflow-x: auto;
}

.services-table {
    width: 100%;
    border-collapse: collapse;
}

.services-table th {
    background: #0b2a4a;
    color: white;
    text-align: left;
    padding: 14px;
}

.services-table td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

.services-table tr:hover {
    background: #f8fafc;
}

.service-icon {
    font-size: 25px;
}

.action {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
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

@media (max-width: 700px) {

    .admin-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .services-table th,
    .services-table td {
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

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

<div class="admin-page">

    <div class="admin-header">

        <div>

            <h1>
                Manage Services
            </h1>

            <p>
                Add, edit and manage website services.
            </p>

        </div>

        <a
            href="dashboard.php"
            class="dashboard-btn"
        >
            ← Dashboard
        </a>

    </div>

    <?php if (isset($_GET["success"])): ?>

        <div class="success">

            Service berhasil ditambahkan.

        </div>

    <?php endif; ?>

    <?php if ($error !== ""): ?>

        <div class="error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>

    <div class="card">

        <h2>
            Add New Service
        </h2>

        <form method="POST">

            <input
                type="hidden"
                name="add_service"
                value="1"
            >

            <div class="form-group">

                <label>
                    Service Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Example: Marine Survey"
                    maxlength="100"
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
                    placeholder="Example: ⚓"
                    maxlength="20"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    placeholder="Describe this service..."
                    required
                ></textarea>

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                + Add Service
            </button>

        </form>

    </div>

    <h2 style="color:#0b2a4a;">
        All Services
    </h2>

    <div class="card">

        <div class="table-wrap">

            <table class="services-table">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Icon</th>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php if (count($services) > 0): ?>

                    <?php foreach ($services as $service): ?>

                        <tr>

                            <td>
                                #<?= (int)$service["id"] ?>
                            </td>

                            <td>

                                <span class="service-icon">
                                    <?= htmlspecialchars($service["icon"]) ?>
                                </span>

                            </td>

                            <td>

                                <strong>
                                    <?= htmlspecialchars($service["name"]) ?>
                                </strong>

                            </td>

                            <td>

                                <?= htmlspecialchars($service["description"]) ?>

                            </td>

                            <td>

                                <div class="action">

                                    <a
                                        href="edit_service.php?id=<?= (int)$service["id"] ?>"
                                        class="btn edit-btn"
                                    >
                                        ✏️ Edit
                                    </a>

                                    <a
                                        href="delete_service.php?id=<?= (int)$service["id"] ?>"
                                        class="btn delete-btn"
                                        onclick="return confirm('Yakin ingin menghapus service ini?');"
                                    >
                                        🗑️ Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="5">

                            Belum ada service.

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