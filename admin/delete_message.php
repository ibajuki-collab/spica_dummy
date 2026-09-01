<?php

require "../config/database.php";
require "../config/auth.php";

require_login();

$id = (int) ($_GET["id"] ?? 0);

if ($id > 0) {

    $stmt = $pdo->prepare(
        "DELETE FROM messages WHERE id = ?"
    );

    $stmt->execute([$id]);
}

header("Location: manage_messages.php");
exit;