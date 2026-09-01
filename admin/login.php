<?php

require "../config/database.php";
session_start();

if (!empty($_SESSION["admin_logged_in"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $u = $_POST["username"] ?? "";
    $p = $_POST["password"] ?? "";

    $stmt = $pdo->prepare(
        "SELECT * FROM admins WHERE username=? LIMIT 1"
    );

    $stmt->execute([$u]);
    $admin = $stmt->fetch();

    if (
        $admin &&
        password_verify($p, $admin["password"])
    ) {

    session_regenerate_id(true);

        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = $admin["username"];

        header("Location: dashboard.php");
        exit;
    }

    $error = "Username atau password salah.";
}

?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="admin-body">

    <div class="admin-wrap" style="max-width:450px">

        <div class="card">

            <h2>Admin</h2>

            <p>
                Login untuk melihat pesan dan data website.
            </p>

            <?php if ($error): ?>
                <div class="notice error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form class="form" method="post">

                <div>
                    <label>Username</label>
                    <input name="username" required>
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button class="btn btn-primary">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>

</html>