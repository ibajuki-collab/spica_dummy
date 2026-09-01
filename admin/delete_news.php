<?php

require "../config/database.php";
require "../config/auth.php";

require_login();
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;

if ($id > 0) {

    $stmt = $pdo->prepare(
        "DELETE FROM news WHERE id = ?"
    );

    $stmt->execute([$id]);
}

header("Location: manage_news.php?success=deleted");
exit;