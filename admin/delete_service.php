<?php

require "../config/database.php";
require "../config/auth.php";

require_login();


$id = (int)($_GET["id"] ?? 0);

if ($id <= 0) {
    header("Location: manage_services.php");
    exit;
}


$stmt = $pdo->prepare("
    SELECT id
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$service = $stmt->fetch();


// Kalau tidak ditemukan
if (!$service) {
    header("Location: manage_services.php?error=notfound");
    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM services
    WHERE id = ?
");

$stmt->execute([$id]);
header("Location: manage_services.php?deleted=1");
exit;

?>